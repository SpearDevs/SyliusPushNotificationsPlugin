<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Command;

use SpearDevs\SyliusPushNotificationsPlugin\Context\ChannelContextInterface;
use SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationTemplate\PushNotificationTemplate;
use SpearDevs\SyliusPushNotificationsPlugin\Factory\Interfaces\WebPushFactoryInterface;
use SpearDevs\SyliusPushNotificationsPlugin\ParameterMapper\ParameterMapperInterface;
use SpearDevs\SyliusPushNotificationsPlugin\Repository\PushNotificationTemplate\PushNotificationTemplateRepository;
use SpearDevs\SyliusPushNotificationsPlugin\WebPushSender\WebPushSenderInterface;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;
use Webmozart\Assert\Assert;

class WebPushNotificationCommand extends Command
{
    protected static $defaultName = 'speardevs:webpush:send';

    private SymfonyStyle $io;

    public const PUSH_NOTIFICATION_CUSTOM_TYPE = 'Custom';

    public function __construct(
        private WebPushSenderInterface $webPushSender,
        private PushNotificationTemplateRepository $pushNotificationTemplateRepository,
        private ChannelRepositoryInterface $channelRepository,
        private ChannelContextInterface $channelContext,
        private WebPushFactoryInterface $webPushFactory,
        private ParameterMapperInterface $orderParameterMapper,
        string $name = null,
    ) {
        parent::__construct($name);
    }

    /**
     * @inheritdoc
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Send default web push notification.')
            ->addArgument('title', InputArgument::REQUIRED, 'Push notification title')
            ->addArgument('content', InputArgument::REQUIRED, 'Push notification content')
            ->addOption(
                'force',
                'f',
                InputOption::VALUE_NONE,
                'Use flag to force the execution of this command',
            )
            ->addArgument('template', InputArgument::REQUIRED, 'A push notification template');
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

        $pushTemplates = $this->pushNotificationTemplateRepository->findAll();
        $channels = $this->channelRepository->findAll();

        $channelChoices = array_map(function ($channel) {
            return $channel->getCode();
        }, $channels);

        $channelQuestion = new ChoiceQuestion(
            '<question>Choose channel</question>',
            $channelChoices,
            0,
        );
        $channelQuestion->setErrorMessage('Channel %s does not exists.');
        $channelChoice = $this->getHelper('question')->ask($input, $output, $channelQuestion);

        $output->writeln('<info>You have just selected: ' . $channelChoice . '</info>');

        $this->channelContext->setChannelCode($channelChoice);

        $choices = array_map(function ($template) {
            return $template->getTitle();
        }, $pushTemplates);

        array_unshift($choices, self::PUSH_NOTIFICATION_CUSTOM_TYPE);

        $question = new ChoiceQuestion(
            '<question>Which push notification template would you like to choose?</question>',
            $choices,
            0,
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

        $input->setArgument('template', $choice);
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

        $webPush = $this->webPushFactory->create($this->orderParameterMapper, null, null, $pushTitle, $pushContent);

        /** @var ChannelInterface $channel */
        $channel = $this->channelContext->getChannel();
        $this->webPushSender->sendToGroup($webPush, $channel);

        $output->write('The push notification was sent successfully.');

        return Command::SUCCESS;
    }

    private function setCommandArgumentForCustomChoice(InputInterface $input, string $choice): void
    {
        $title = $input->getArgument('title');
        $titleMessage = null !== $title ? ' > <info>Title</info>: ' . $title : 'Enter push notification title';
        $title = $this->io->ask($titleMessage, $title);

        Assert::notNull($title, 'The title can not be empty.');
        $input->setArgument('title', $title);

        $content = $input->getArgument('content');
        $contentMessage = null !== $content ? ' > <info>Content</info>: ' . $content : 'Enter push notification content';
        $content = $this->io->ask($contentMessage, $content);

        Assert::notNull($content, 'The content can not be empty.');
        $input->setArgument('content', $content);
        $input->setArgument('template', $choice);
    }
}
