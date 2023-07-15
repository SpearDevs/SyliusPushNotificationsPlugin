<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Controller\Admin;

use SpearDevs\SyliusPushNotificationsPlugin\Context\ChannelContextInterface;
use SpearDevs\SyliusPushNotificationsPlugin\Form\Type\Admin\SendPushNotificationType;
use SpearDevs\SyliusPushNotificationsPlugin\WebPush\WebPush;
use SpearDevs\SyliusPushNotificationsPlugin\WebPushSender\WebPushSenderInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

final class SendPushNotificationAction extends AbstractController
{
    public const USER_RECEIVER = 'user';

    public const GROUP_RECEIVER = 'group';

    public function __construct(
        private Environment $twig,
        private TranslatorInterface $translator,
        private WebPushSenderInterface $webPushSender,
        private ChannelContextInterface $channelContext,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(SendPushNotificationType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $pushTitle = $data['title'] ?? '';
            $pushContent = $data['body'] ?? '';
            $customerGroup = $data['groups']?->getName() ?? '';
            $receiver = $data['receiver'] ?? '';
            $user = $data['user']?->getEmail() ?? '';

            $this->channelContext->setChannelCode($data['channel']->getCode());

            /** @var ChannelInterface $channel */
            $channel = $this->channelContext->getChannel();

            $webPush = new WebPush(null, null, $pushTitle, $pushContent);

            if ($receiver === self::USER_RECEIVER) {
                $this->webPushSender->sendToUser($webPush, $channel, $user);
            }

            if ($receiver === self::GROUP_RECEIVER) {
                $this->webPushSender->sendToGroup($webPush, $channel, $customerGroup);
            }

            $this->addFlash('success', $this->translator->trans('speardevs_sylius_push_notifications_plugin.ui.sent_success'));

            return $this->redirect($request->getUri());
        }

        return new Response($this->twig->render(
            $request->get('template'),
            ['form' => $form->createView()],
        ));
    }
}
