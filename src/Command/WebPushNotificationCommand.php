<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Command;

use BenTools\WebPushBundle\Model\Message\PushNotification;
use BenTools\WebPushBundle\Sender\PushMessageSender;
use SpearDevs\SyliusPushNotificationsPlugin\Services\UserSubscriptionManager;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Sylius\Component\User\Model\User;

class WebPushNotificationCommand extends Command
{
    protected static $defaultName = 'spear-devs:webpush:send';

    public function __construct(
        private RepositoryInterface $shopUserRepository,
        private UserSubscriptionManager $userSubscriptionManager,
        private PushMessageSender $sender,
        string $name = null
    )
    {
        parent::__construct($name);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setName('spear-devs-web:webpush:send')
            ->setDescription('Send default web push notification.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $users = $this->shopUserRepository->findAll();

        /** @var  $user User */
        foreach ($users as $user) {
            $subscriptions = $this->userSubscriptionManager->findByUser($user);

            if (!count($subscriptions)) {
                $notification = new PushNotification('Test Message!', [
                    PushNotification::BODY => 'ĄŚŻŹĆ.',
                ]);

                $responses = $this->sender->push($notification->createMessage(), $subscriptions);

                foreach ($responses as $response) {
                    if ($response->isExpired()) {
                        $this->userSubscriptionManager->delete($response->getSubscription());
                    }
                }
            }
        }

        return Command::SUCCESS;
    }
}
