![img.png](images/img_header.png)

## Usage

The class is responsible for sending push notifications

`SpearDevs\SyliusPushNotificationsPlugin\WebPushSender`

Here you can use 3 class methods:

```php
    public function sendToGroup(WebPushInterface $webPush, ChannelInterface $channel, ?string $receiver = null): void;

    public function sendToUser(WebPushInterface $webPush, ChannelInterface $channel, ?string $receiver = null): void;

    public function sendOrderWebPush(OrderInterface $order, string $pushNotificationCode, ChannelInterface $channel): void;
```


Below you will find an example of a function that is used to send push notifications when a product is available. This function can be used in an event or in a command that will be configured from cron.
As you can see, the `$webPush` object is created by `WebPushFactoryInterface`.

#### `$webPushFactory` needs:
- A class that extends `SpearDevs\SyliusPushNotificationsPlugin\ParameterMapper\AbstractParameterMapper`
- A class that implement `Sylius\Component\Resource\Model\ResourceInterface`
- and object of class `SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationTemplate\PushNotificationTemplate`

You can pass the object `$webPush` created this way to the function `sendToUser` along with actual Channel as `$channel` and the user's email as `$receiver` to send push notification.

```php
# Example of WebPushManager you can implement
<?php

declare(strict_types=1);

namespace App\WebPush;

use App\Entity\Product\Product;
use App\WebPush\ParameterMapper\ProductParameterMapper;
use SpearDevs\SyliusPushNotificationsPlugin\Context\ChannelContextInterface;
use SpearDevs\SyliusPushNotificationsPlugin\Factory\Interfaces\WebPushFactoryInterface;
use SpearDevs\SyliusPushNotificationsPlugin\Repository\PushNotificationTemplate\PushNotificationTemplateRepositoryInterface;
use SpearDevs\SyliusPushNotificationsPlugin\WebPushSender\WebPushSenderInterface;
use Sylius\Component\Core\Model\ChannelInterface;

final class WebPushManager
{
    public function __construct(
        private readonly WebPushSenderInterface $webPushSender,
        private readonly WebPushFactoryInterface $webPushFactory,
        private readonly ProductParameterMapper $productParameterMapper,
        private readonly PushNotificationTemplateRepositoryInterface $pushNotificationTemplateRepository,
        private readonly ChannelContextInterface $channelContext,
    ) {
    }

    public function sendProductAvailableWebPush(
        string $receiver,
        ChannelInterface $channel,
        Product $product,
        string $pushNotificationTemplateCode
    ): void {
        $pushNotificationTemplate = $this->pushNotificationTemplateRepository->findOneBy([
            'code' => $pushNotificationTemplateCode,
        ]);

        $this->channelContext->setChannelCode($channel->getCode());

        $webPush = $this->webPushFactory->create($this->productParameterMapper, $product, $pushNotificationTemplate);

        $this->webPushSender->sendToUser($webPush, $channel, $receiver);
    }
}
```

```php
# Example of parameter mapper extends AbstractParameterMapper you can implement
<?php

declare(strict_types=1);

namespace App\WebPush\ParameterMapper;

use App\Entity\Product\Product;
use SpearDevs\SyliusPushNotificationsPlugin\ParameterMapper\AbstractParameterMapper;
use Sylius\Component\Resource\Model\ResourceInterface;
use Webmozart\Assert\Assert;

final class ProductParameterMapper extends AbstractParameterMapper
{
    public function mapParameters(ResourceInterface $product, string $text): string
    {
        Assert::isInstanceOf(
            $product,
            Product::class,
            'Mapper can be used with an entity: App\Entity\Product\Product',
        );

        /** @var Product $product */
        $productData = $this->getProductData($product);

        //{product_name} can be used in push notification template in title or in content
        //You can add more variables example product brand or model name
        $change = [
            '{product_name}' => $productData['product_name'],
        ];

        return strtr($text, $change);
    }

    private function getProductData(Product $product): array
    {
        return [
            'product_name' => $product->getName(),
        ];
    }
}
```

### State Machine callbacks:

By default, the push notification engine has two actions configured when changing the order and the shipment state. If you want to send notifications during another activity or permanently opt out of such sending, below you will find an example of how to do it.

```yaml
winzou_state_machine:
    sylius_order:
        callbacks:
            after:
                sylius_send_web_push:
                    on: [ 'create' ]
                    do: [ '@SpearDevs\SyliusPushNotificationsPlugin\WebPushSender\WebPushSender', 'sendOrderWebPush' ]
                    args: [ 'object', !php/const SpearDevs\SyliusPushNotificationsPlugin\WebPushSender\WebPushSender::PUSH_NEW_ORDER_CODE, 'object.getChannel()' ]
                    priority: 100
```

```yaml
winzou_state_machine:
    sylius_shipment:
        callbacks:
            after:
                sylius_send_web_push:
                    on: [ 'ship' ]
                    do: [ '@SpearDevs\SyliusPushNotificationsPlugin\WebPushSender\WebPushSender', 'sendOrderWebPush' ]
                    args: ['object.getOrder()', !php/const SpearDevs\SyliusPushNotificationsPlugin\WebPushSender\WebPushSender::PUSH_ORDER_SHIPPED_CODE, 'object.getOrder().getChannel()' ]
                    priority: 100
```

Example of turning callback:
```yaml
winzou_state_machine:
    sylius_order:
        callbacks:
            after:
                sylius_send_web_push:
                    disabled: true
```
