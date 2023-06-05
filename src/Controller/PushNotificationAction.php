<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

final class PushNotificationAction extends AbstractController
{
    public function __construct(private Environment $twig) {
    }

    public function __invoke(Request $request): Response
    {
        $template = $request->get('template');

        return new Response($this->twig->render(
            $template,
        ));
    }
}
