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
        //When
        $endpoint = 'example.com';
        $user = $this->createMock(ShopUser::class);
        $expectedHash = md5($endpoint);

        //Given
        $hashedEndpoint = $this->userSubscriptionManager->hash($endpoint, $user);

        //Then
        Assert::assertEquals($expectedHash, $hashedEndpoint);
    }

    public function testGetUserSubscription(): void
    {
        //Given
        $user = $this->createMock(ShopUser::class);
        $subscriptionHash = 'subscriptionHash';
        $userSubscription = $this->createMock(UserSubscription::class);

        $this->userSubscriptionRepository->expects(self::once())
            ->method('findOneBy')
            ->with(['user' => $user, 'subscriptionHash' => $subscriptionHash])
            ->willReturn($userSubscription);

        //WHen
        $result = $this->userSubscriptionManager->getUserSubscription($user, $subscriptionHash);

        //Then
        Assert::assertSame($userSubscription, $result);
    }

    public function testFindByUser(): void
    {
        //Given
        $user = $this->createMock(ShopUser::class);
        $userSubscription = $this->createMock(UserSubscription::class);

        $userSubscriptions = [$userSubscription];

        $this->userSubscriptionRepository->expects(self::once())
            ->method('findBy')
            ->with(['user' => $user])
            ->willReturn($userSubscriptions);

        //When
        $result = $this->userSubscriptionManager->findByUser($user);

        //Then
        Assert::assertEquals($userSubscriptions, $result);
    }

    public function testFindByHash(): void
    {
        //Given
        $subscriptionHash = 'subscriptionHash';
        $userSubscription = $this->createMock(UserSubscription::class);

        $userSubscriptions = [$userSubscription];

        $this->userSubscriptionRepository->expects(self::once())
            ->method('findBy')
            ->with(['subscriptionHash' => $subscriptionHash])
            ->willReturn($userSubscriptions);

        //When
        $result = $this->userSubscriptionManager->findByHash($subscriptionHash);

        //Then
        Assert::assertEquals($userSubscriptions, $result);
    }

    public function testSave(): void
    {
        //Given
        $userSubscription = $this->createMock(UserSubscription::class);
        $user = $this->createMock(ShopUser::class);
        $request = $this->createMock(Request::class);
        $channel = $this->createMock(Channel::class);
        $customer = $this->createMock(CustomerInterface::class);

        //Then
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

        //When
        $this->userSubscriptionManager->save($userSubscription);
    }

    public function testDelete(): void
    {
        //Given
        $userSubscription = $this->createMock(UserSubscription::class);

        //Then
        $this->userSubscriptionRepository->expects(self::once())
            ->method('remove')
            ->with($userSubscription);

        //When
        $this->userSubscriptionManager->delete($userSubscription);
    }
}
