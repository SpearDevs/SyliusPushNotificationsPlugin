<?php

declare(strict_types=1);

namespace Tests\SpearDevs\SyliusPushNotificationsPlugin\Unit\Manager;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SpearDevs\SyliusPushNotificationsPlugin\Entity\UserSubscription\UserSubscription;
use SpearDevs\SyliusPushNotificationsPlugin\Manager\UserSubscriptionManager;
use SpearDevs\SyliusPushNotificationsPlugin\Repository\UserSubscriptionRepositoryInterface;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Core\Model\Channel;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\ShopUser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class UserSubscriptionManagerTest extends TestCase
{
    /** @var UserSubscriptionRepositoryInterface&MockObject */
    private UserSubscriptionRepositoryInterface $userSubscriptionRepository;

    /** @var ChannelRepositoryInterface&MockObject */
    private ChannelRepositoryInterface $channelRepository;

    /** @var RequestStack&MockObject */
    private RequestStack $requestStack;

    private UserSubscriptionManager $userSubscriptionManager;

    protected function setUp(): void
    {
        $this->userSubscriptionRepository = $this->createMock(UserSubscriptionRepositoryInterface::class);
        $this->channelRepository = $this->createMock(ChannelRepositoryInterface::class);
        $this->requestStack = $this->createMock(RequestStack::class);

        $this->userSubscriptionManager = new UserSubscriptionManager(
            $this->userSubscriptionRepository,
            $this->channelRepository,
            $this->requestStack,
        );
    }

    public function testFactory(): void
    {
        //Given
        $user = $this->createMock(ShopUser::class);
        $subscriptionHash = 'subscriptionHash';
        $subscription = ['endpoint' => 'example.com', 'keys' => ['p256dh' => 'p256dh_key', 'auth' => 'auth_key']];

        //When
        $userSubscription = $this->userSubscriptionManager->factory($user, $subscriptionHash, $subscription);

        //Then
        Assert::assertInstanceOf(UserSubscription::class, $userSubscription);
        Assert::assertSame($user, $userSubscription->getUser());
        Assert::assertEquals($subscriptionHash, $userSubscription->getSubscriptionHash());
    }

    public function testHash(): void
    {
        $endpoint = 'example.com';
        $user = $this->createMock(ShopUser::class);

        $hashedEndpoint = $this->userSubscriptionManager->hash($endpoint, $user);
        $expectedHash = md5($endpoint);

        Assert::assertEquals($expectedHash, $hashedEndpoint);
    }

    public function testGetUserSubscription(): void
    {
        $user = $this->createMock(ShopUser::class);
        $subscriptionHash = 'subscriptionHash';
        $userSubscription = $this->createMock(UserSubscription::class);

        $this->userSubscriptionRepository->expects(self::once())
            ->method('findOneBy')
            ->with(['user' => $user, 'subscriptionHash' => $subscriptionHash])
            ->willReturn($userSubscription);

        $result = $this->userSubscriptionManager->getUserSubscription($user, $subscriptionHash);
        Assert::assertSame($userSubscription, $result);
    }

    public function testFindByUser(): void
    {
        $user = $this->createMock(ShopUser::class);
        $userSubscription = $this->createMock(UserSubscription::class);

        $userSubscriptions = [$userSubscription];

        $this->userSubscriptionRepository->expects(self::once())
            ->method('findBy')
            ->with(['user' => $user])
            ->willReturn($userSubscriptions);

        $result = $this->userSubscriptionManager->findByUser($user);
        Assert::assertEquals($userSubscriptions, $result);
    }

    public function testFindByHash(): void
    {
        $subscriptionHash = 'subscriptionHash';
        $userSubscription = $this->createMock(UserSubscription::class);

        $userSubscriptions = [$userSubscription];

        $this->userSubscriptionRepository->expects(self::once())
            ->method('findBy')
            ->with(['subscriptionHash' => $subscriptionHash])
            ->willReturn($userSubscriptions);

        $result = $this->userSubscriptionManager->findByHash($subscriptionHash);
        Assert::assertEquals($userSubscriptions, $result);
    }

    public function testSave(): void
    {
        $userSubscription = $this->createMock(UserSubscription::class);
        $user = $this->createMock(ShopUser::class);
        $request = $this->createMock(Request::class);
        $channel = $this->createMock(Channel::class);
        $customer = $this->createMock(CustomerInterface::class);

        $this->requestStack->expects(self::once())
            ->method('getCurrentRequest')
            ->willReturn($request);

        $request->expects(self::once())
            ->method('getHttpHost')
            ->willReturn('example.com');

        $this->channelRepository->expects(self::once())
            ->method('findOneEnabledByHostname')
            ->with('example.com')
            ->willReturn($channel);

        $userSubscription->expects(self::once())
            ->method('getUser')
            ->willReturn($user);

        $user->expects(self::once())
            ->method('getCustomer')
            ->willReturn($customer);

        $this->userSubscriptionRepository->expects(self::once())
            ->method('add')
            ->with($userSubscription);

        $this->userSubscriptionManager->save($userSubscription);
    }

    public function testDelete(): void
    {
        $userSubscription = $this->createMock(UserSubscription::class);

        $this->userSubscriptionRepository->expects(self::once())
            ->method('remove')
            ->with($userSubscription);

        $this->userSubscriptionManager->delete($userSubscription);
    }
}
