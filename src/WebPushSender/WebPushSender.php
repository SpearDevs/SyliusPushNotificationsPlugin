<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\WebPushSender;

use BenTools\WebPushBundle\Model\Message\PushNotification;
use BenTools\WebPushBundle\Model\Subscription\UserSubscriptionManagerInterface;
use BenTools\WebPushBundle\Sender\PushMessageSender;
use Psr\Log\LoggerInterface;
use SpearDevs\SyliusPushNotificationsPlugin\Context\ChannelContextInterface;
use SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationTemplate\PushNotificationTemplate;
use SpearDevs\SyliusPushNotificationsPlugin\Factory\Interfaces\WebPushFactoryInterface;
use SpearDevs\SyliusPushNotificationsPlugin\Form\Model\SendPushNotificationFormModel;
use SpearDevs\SyliusPushNotificationsPlugin\ParameterMapper\ParameterMapperInterface;
use SpearDevs\SyliusPushNotificationsPlugin\Repository\PushNotificationTemplate\PushNotificationTemplateRepositoryInterface;
use SpearDevs\SyliusPushNotificationsPlugin\Repository\UserSubscriptionRepositoryInterface;
use SpearDevs\SyliusPushNotificationsPlugin\Service\PushNotificationConfigurationService;
use SpearDevs\SyliusPushNotificationsPlugin\Service\WebPushHistoryCreator\WebPushHistoryCreatorInterface;
use SpearDevs\SyliusPushNotificationsPlugin\WebPush\WebPushInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\User\Model\UserInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Translation\TranslatableMessage;
use Traversable;

final class WebPushSender implements WebPushSenderInterface
{
    public const PUSH_NEW_ORDER_CODE = "'push_new_order'";

    public const PUSH_ORDER_SHIPPED_CODE = "'push_order_shipped'";

    public const USER_RECEIVER = 'user';

    public const GROUP_RECEIVER = 'group';

    public function __construct(
        private UserSubscriptionRepositoryInterface         $userSubscriptionRepository,
        private UserSubscriptionManagerInterface            $userSubscriptionManager,
        private PushMessageSender                           $sender,
        private PushNotificationTemplateRepositoryInterface $pushNotificationTemplateRepository,
        private PushNotificationConfigurationService        $pushNotificationConfigurationService,
        private WebPushHistoryCreatorInterface              $webPushHistoryCreator,
        private ChannelContextInterface                     $channelContext,
        private WebPushFactoryInterface                     $webPushFactory,
        private ParameterMapperInterface                    $orderParameterMapper,
        private LoggerInterface                             $logger,
        private SessionInterface                            $session,
    )
    {
    }

    public function sendWebPush(SendPushNotificationFormModel $sendPushNotificationFormModel): void
    {
        $pushTitle = $sendPushNotificationFormModel->title;
        $pushContent = $sendPushNotificationFormModel->body;
        $receiver = $sendPushNotificationFormModel->receiver;
        $channel = $sendPushNotificationFormModel->channel;

        $webPush = $this->webPushFactory->create($this->orderParameterMapper, null, null, $pushTitle, $pushContent);

        if ($receiver === self::USER_RECEIVER) {
            $userEmail = $sendPushNotificationFormModel->userEmail;
            $this->sendToUser($webPush, $channel, $userEmail);
        }

        if ($receiver === self::GROUP_RECEIVER) {
            $customerGroup = $sendPushNotificationFormModel->group;
            $this->sendToGroup($webPush, $channel, $customerGroup?->getName());
        }
    }

    public function sendToGroup(WebPushInterface $webPush, ChannelInterface $channel, ?string $receiver = null): void
    {
        $subscriptions = ($receiver) ?
            $this->userSubscriptionRepository->getSubscriptionsForUsersInGroup($receiver, $channel) :
            $this->userSubscriptionRepository->getSubscriptionsForAllUsers($channel);

        $this->send($webPush, $subscriptions);
    }

    public function sendToUser(WebPushInterface $webPush, ChannelInterface $channel, ?string $receiver = null): void
    {
        $subscriptions = $this->userSubscriptionRepository->getSubscriptionsForUserByEmail($receiver, $channel);

        $this->send($webPush, $subscriptions);
    }

    public function sendOrderWebPush(OrderInterface $order, string $pushNotificationCode, ChannelInterface $channel): void
    {
        /** @var PushNotificationTemplate $pushNotificationTemplate */
        $pushNotificationTemplate = $this->pushNotificationTemplateRepository->findOneBy(['code' => $pushNotificationCode]);

        /** @var UserInterface $user */
        $user = $order->getCustomer()->getUser();

        if ($user) {
            $this->channelContext->setChannelCode($channel->getCode());

            $webPush = $this->webPushFactory->create($this->orderParameterMapper, $order, $pushNotificationTemplate);
            try {
                $this->sendToUser(
                    $webPush,
                    $channel,
                    $user->getEmail(),
                );
            } catch (\Exception $e) {
                $this->session->getFlashBag()->add(
                    'error',
                    new TranslatableMessage('speardevs_sylius_push_notifications_plugin.ui.sent_error')
                );
                $this->logger->error('Problem while sending push notifications ' . $e->getMessage());
            }
        }

    }

    private function send(WebPushInterface $webPush, iterable $subscriptions): void
    {
        $notification = new PushNotification($webPush->getTitle(), [
            PushNotification::BODY => $webPush->getContent(),
            PushNotification::ICON => $this->pushNotificationConfigurationService->getLinkToPushNotificationIcon(),
        ]);

        /** @var Traversable $subscriptions * */
        $subscriptionsArray = iterator_to_array($subscriptions);

        $responses = $this->sender->push($notification->createMessage(), $subscriptionsArray);

        foreach ($responses as $response) {
            $this->webPushHistoryCreator->create($webPush, $response);

            if ($response->isExpired()) {
                $this->userSubscriptionManager->delete($response->getSubscription());
            }
        }
    }
}
