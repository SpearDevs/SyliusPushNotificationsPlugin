<?php

declare(strict_types=1);

namespace Tests\SpearDevs\SyliusPushNotificationsPlugin\Unit\Manager;

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

    /** @var UserSubscriptionManager&MockObject */
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

    public function testFactory()
    {
        //Given
        $user = $this->createMock(ShopUser::class);
        $subscriptionHash = 'subscriptionHash';
        $subscription = ['endpoint' => 'example.com', 'keys' => ['p256dh' => 'p256dh_key', 'auth' => 'auth_key']];

        //When
        $userSubscription = $this->userSubscriptionManager->factory($user, $subscriptionHash, $subscription);

        //Then
        $this->assertInstanceOf(UserSubscription::class, $userSubscription);
        $this->assertSame($user, $userSubscription->getUser());
        $this->assertEquals($subscriptionHash, $userSubscription->getSubscriptionHash());
    }

    public function testHash()
    {
        $endpoint = 'example.com';
        $user = $this->createMock(ShopUser::class);

        $hashedEndpoint = $this->userSubscriptionManager->hash($endpoint, $user);
        $expectedHash = md5($endpoint);

        $this->assertEquals($expectedHash, $hashedEndpoint);
    }

    public function testGetUserSubscription()
    {
        $user = $this->createMock(ShopUser::class);
        $subscriptionHash = 'subscriptionHash';
        $userSubscription = $this->createMock(UserSubscription::class);

        $this->userSubscriptionRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['user' => $user, 'subscriptionHash' => $subscriptionHash])
            ->willReturn($userSubscription);

        $result = $this->userSubscriptionManager->getUserSubscription($user, $subscriptionHash);
        $this->assertSame($userSubscription, $result);
    }

    public function testFindByUser()
    {
        $user = $this->createMock(ShopUser::class);
        $userSubscription = $this->createMock(UserSubscription::class);

        $userSubscriptions = [$userSubscription];

        $this->userSubscriptionRepository->expects($this->once())
            ->method('findBy')
            ->with(['user' => $user])
            ->willReturn($userSubscriptions);

        $result = $this->userSubscriptionManager->findByUser($user);
        $this->assertEquals($userSubscriptions, $result);
    }

    public function testFindByHash()
    {
        $subscriptionHash = 'subscriptionHash';
        $userSubscription = $this->createMock(UserSubscription::class);

        $userSubscriptions = [$userSubscription];

        $this->userSubscriptionRepository->expects($this->once())
            ->method('findBy')
            ->with(['subscriptionHash' => $subscriptionHash])
            ->willReturn($userSubscriptions);

        $result = $this->userSubscriptionManager->findByHash($subscriptionHash);
        $this->assertEquals($userSubscriptions, $result);
    }

    public function testSave()
    {
        $userSubscription = $this->createMock(UserSubscription::class);
        $user = $this->createMock(ShopUser::class);
        $request = $this->createMock(Request::class);
        $channel = $this->createMock(Channel::class);
        $customer = $this->createMock(CustomerInterface::class);

        $this->requestStack->expects($this->once())
            ->method('getCurrentRequest')
            ->willReturn($request);

        $request->expects($this->once())
            ->method('getHttpHost')
            ->willReturn('example.com');

        $this->channelRepository->expects($this->once())
            ->method('findOneEnabledByHostname')
            ->with('example.com')
            ->willReturn($channel);

        $userSubscription->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        $user->expects($this->once())
            ->method('getCustomer')
            ->willReturn($customer);

        $this->userSubscriptionRepository->expects($this->once())
            ->method('add')
            ->with($userSubscription);

        $this->userSubscriptionManager->save($userSubscription);
    }

    public function testDelete()
    {
        $userSubscription = $this->createMock(UserSubscription::class);

        $this->userSubscriptionRepository->expects($this->once())
            ->method('remove')
            ->with($userSubscription);

        $this->userSubscriptionManager->delete($userSubscription);
    }
}
