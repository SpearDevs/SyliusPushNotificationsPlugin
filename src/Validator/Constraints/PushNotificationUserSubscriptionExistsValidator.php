<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Validator\Constraints;

use SpearDevs\SyliusPushNotificationsPlugin\Form\Model\SendPushNotificationFormModel;
use SpearDevs\SyliusPushNotificationsPlugin\Repository\UserSubscriptionRepositoryInterface;
use SpearDevs\SyliusPushNotificationsPlugin\WebPushSender\WebPushSender;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Traversable;

final class PushNotificationUserSubscriptionExistsValidator extends ConstraintValidator
{
    public function __construct(
        private UserSubscriptionRepositoryInterface $userSubscriptionRepository,
    ) {
    }

    /**
     * @param SendPushNotificationFormModel $value
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof PushNotificationUserSubscriptionExists) {
            throw new UnexpectedTypeException($constraint, PushNotificationUser::class);
        }

        if ($value === null || $value === '' || $value->receiver === WebPushSender::GROUP_RECEIVER) {
            return;
        }

        $subscriptions = $this->userSubscriptionRepository->getSubscriptionsForUserByEmail(
            $value->userEmail,
            $value->channel,
        );

        /** @var Traversable $subscriptions * */
        $subscriptionCount = iterator_count($subscriptions);

        if ($subscriptionCount > 0) {
            return;
        }

        $this->context
            ->buildViolation($constraint->message)
            ->atPath('userEmail')
            ->addViolation();
    }
}
