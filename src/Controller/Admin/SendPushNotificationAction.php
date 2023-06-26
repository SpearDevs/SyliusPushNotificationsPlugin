<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Controller\Admin;

use SpearDevs\SyliusPushNotificationsPlugin\Form\Type\Admin\SendPushNotificationType;
use SpearDevs\SyliusPushNotificationsPlugin\Handler\PushNotificationHandler;
use SpearDevs\SyliusPushNotificationsPlugin\Handler\PushNotificationHandlerFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Symfony\Contracts\Translation\TranslatorInterface;

final class SendPushNotificationAction extends AbstractController
{
    public function __construct(
        private Environment $twig,
        private TranslatorInterface $translator,
        private PushNotificationHandlerFactory $pushNotificationHandlerFactory
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
            $groupCustomer = $data['groups'] ?? null;
            $receiver = $data['receiver'] ?? '';
            $user = $data['user'];

            if ($user) {
                $receiverObject = $user;
            } else {
                $receiverObject = $groupCustomer;
            }


            $pushNotificationHandler = $this->pushNotificationHandlerFactory->getPushNotificationHandler($receiver);

            $pushNotificationHandler->sendToReceiver($pushTitle, $pushContent, $receiverObject);


            $this->addFlash('success', $this->translator->trans('speardevs.ui.sent_success'));

            return $this->redirect($request->getUri());
        }

        return new Response($this->twig->render(
            $request->get('template'),
            ['form' => $form->createView()]
        ));
    }
}
