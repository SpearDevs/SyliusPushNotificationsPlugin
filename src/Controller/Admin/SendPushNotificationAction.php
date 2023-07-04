<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Controller\Admin;

use SpearDevs\SyliusPushNotificationsPlugin\Form\Type\Admin\SendPushNotificationType;
use SpearDevs\SyliusPushNotificationsPlugin\Handler\PushNotificationHandlerInterface;
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
        private PushNotificationHandlerInterface $pushNotificationHandler
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

            if ($receiver === 'user') {
                $this->pushNotificationHandler->sendToUser($pushTitle, $pushContent, $user);
            }

            if ($receiver === 'group') {
                $this->pushNotificationHandler->sendToGroup($pushTitle, $pushContent, $customerGroup);
            }

            $this->addFlash('success', $this->translator->trans('speardevs_sylius_push_notifications_plugin.ui.sent_success'));

            return $this->redirect($request->getUri());
        }

        return new Response($this->twig->render(
            $request->get('template'),
            ['form' => $form->createView()]
        ));
    }
}
