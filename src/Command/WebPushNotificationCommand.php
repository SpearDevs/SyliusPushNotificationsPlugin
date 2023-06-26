<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Command;

use SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationTemplate;
use SpearDevs\SyliusPushNotificationsPlugin\Handler\PushNotificationHandler;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;
use SpearDevs\SyliusPushNotificationsPlugin\Utils\Validator;

class WebPushNotificationCommand extends Command
{
    protected static $defaultName = 'speardevs:webpush:send';
    private SymfonyStyle $io;

    public function __construct(
        private PushNotificationHandler $pushNotificationHandler,
        private Validator $validator,
        private RepositoryInterface $pushNotificationTemplateRepository,
        string $name = null
    ) {
        parent::__construct($name);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Send default web push notification.')
            ->addArgument('key', InputArgument::REQUIRED, 'A push notification template key')
            ->addArgument('title', InputArgument::REQUIRED, 'Push notification title')
            ->addArgument('content', InputArgument::REQUIRED, 'Push notification content')
            ->addOption(
                'force',
                null,
                InputOption::VALUE_NONE,
                'Use flag to force the execution of this command'
            );
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        if (null !== $input->getArgument('title') && null !== $input->getArgument('content')) {
            return;
        }

        $this->io->title('Web Push Notifications Interactive Wizard');

        $pushTemlpates = $this->pushNotificationTemplateRepository->findAll();

        $choices = array_map(function ($template) {
            return $template->getTitle();
        }, $pushTemlpates);

        array_unshift($choices, 'Custom');

        $question = new ChoiceQuestion(
            '<question>Which push notification template would you like to choose?</question>',
            $choices,
            0
        );
        $question->setErrorMessage('Push notification template %s does not exists.');

        $choice = $this->getHelper('question')->ask($input, $output, $question);

        $output->writeln('<info>You have just selected: ' . $choice . '</info>');

        if ($choice === 'Custom') {
            $this->setCommandArgumentForCustomChoice($input, $choice);
            return;
        }

        /** @var PushNotificationTemplate $pushMessageTemplate */
        $pushMessageTemplate = $this->pushNotificationTemplateRepository->findOneBy(['title' => $choice]);

        $input->setArgument('title', $pushMessageTemplate->getTitle());
        $input->setArgument('content', $pushMessageTemplate->getContent());

        $input->setArgument('key', $choice);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');

        if ($input->getOption('force') === false) {
            $output->writeln('<info>You do not use the --force flag, if you want to avoid this message and</info>');
            $output->writeln('<info>and force send web push notifications, use the --force flag.</info>');

            $question = new ConfirmationQuestion('<question>Do you want to send notification to all users?</question>', false);

            if ($helper->ask($input, $output, $question) === false) {
                return Command::SUCCESS;
            }
        }

        $pushTitle = $input->getArgument('title');
        $pushContent = $input->getArgument('content');

        $this->pushNotificationHandler->sendToReceiver($pushTitle, $pushContent);

        $output->write('The push notification was sent successfully.');

        return Command::SUCCESS;
    }

    private function setCommandArgumentForCustomChoice(InputInterface $input, string $choice): void
    {
        $title = $input->getArgument('title');
        $titleMessage = null !== $title ? ' > <info>Title</info>: ' . $title : 'Enter push notification title';
        $title = $this->io->ask($titleMessage, $title, [$this->validator, 'validateText']);
        $input->setArgument('title', $title);

        $content = $input->getArgument('content');
        $contentMessage = null !== $content ? ' > <info>Content</info>: ' . $content : 'Enter push notification content';
        $content = $this->io->ask($contentMessage, $content, [$this->validator, 'validateText']);
        $input->setArgument('content', $content);
        $input->setArgument('key', $choice);
    }
}
