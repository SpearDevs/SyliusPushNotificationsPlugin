<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Controller\Admin;

use Psr\Log\LoggerInterface;
use SpearDevs\SyliusPushNotificationsPlugin\Context\ChannelContextInterface;
use SpearDevs\SyliusPushNotificationsPlugin\Form\Model\SendPushNotificationFormModel;
use SpearDevs\SyliusPushNotificationsPlugin\Form\Type\Admin\SendPushNotificationType;
use SpearDevs\SyliusPushNotificationsPlugin\WebPushSender\WebPushSenderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\TranslatableMessage;

final class SendPushNotificationAction extends AbstractController
{
    public function __construct(
        private WebPushSenderInterface $webPushSender,
        private ChannelContextInterface $channelContext,
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(SendPushNotificationType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                /** @var SendPushNotificationFormModel $data */
                $data = $form->getData();
                $channel = $data->channel;
                $this->channelContext->setChannelCode($channel->getCode());
                $this->webPushSender->sendWebPush($data);

                $this->addFlash('success', new TranslatableMessage(
                    'speardevs_sylius_push_notifications_plugin.ui.sent_success',
                ));
            } catch (\Exception $exception) {
                $this->logger->error('Problem while sending push notifications ' . $exception->getMessage());

                $this->addFlash('error', new TranslatableMessage(
                    'speardevs_sylius_push_notifications_plugin.ui.sent_error',
                ));
            }

            return $this->redirect($request->getUri());
        }

        return $this->render($request->get('template'), ['form' => $form->createView()]);
    }
}
