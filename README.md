![img.png](docs/images/img_header.png)
# Sylius Push Notification Plugin

Plugin for Sylius, based on the [bpolaszek/webpush-bundle](https://github.com/bpolaszek/webpush-bundle) package, enabling the sending of push notifications within an online store. This plugin provides the functionality to send push notifications in two key scenarios: after order placement and after order shipment. Additionally, it allows for the sending of push notifications to individual users or user groups.

### Documentation:
After installation, read more about using the plugin and its functions for the user and administrator.

1. [More about plugin usage.](docs/usage.md)
2. [Customer section.](docs/customer_experience.md)
3. [Administrator section.](docs/admin_experience.md)

### Instalation

1. Run `composer require speardevs/sylius-push-notifications-plugin`.

## Backend setup

1. Generate your VAPID keys:
```bash
php bin/console webpush:generate:keys
```

2. Set VAPID keys:
.env file:
```
WEBPUSH_PUBLIC_KEY=publickey
WEBPUSH_PRIVATE_KEY=privatekey
```

```yaml
# config/packages/bentools_webpush.yaml (SF4) 
bentools_webpush:
    settings:
        public_key: '%env(WEBPUSH_PUBLIC_KEY)%'
        private_key: '%env(WEBPUSH_PRIVATE_KEY)%'
```

3. Import required config in your `config/packages/_sylius.yaml` file:
```yaml
# config/packages/_sylius.yaml

imports:
    ...

    - { resource: "@SpearDevsSyliusPushNotificationsPlugin/config/config.yaml" }
```

4. If you extend `Sylius\Component\Core\Model\ShopUser` class, you need to register `UserSubscriptionManager` with your `user_class`, 
   for example:
```yaml
# config/services.yaml

SpearDevs\SyliusPushNotificationsPlugin\Manager\UserSubscriptionManager:
    class: SpearDevs\SyliusPushNotificationsPlugin\Manager\UserSubscriptionManager
    tags:
        - { name: bentools_webpush.subscription_manager, user_class: 'App\Entity\User\ShopUser' }
```

5. Update your `bundles.php` file:
```yaml
# config/bundles.php

BenTools\WebPushBundle\WebPushBundle::class => ['all' => true],
SpearDevs\SyliusPushNotificationsPlugin\SpearDevsSyliusPushNotificationsPlugin::class => ['all' => true],
```

6. Configure routing in config/routes.yaml:
```yaml
# config/routes.yaml

speardevs_push_notifications_admin:
    resource: "@SpearDevsSyliusPushNotificationsPlugin/config/routing/admin_routing.yaml"
    prefix: /admin

speardevs_push_notifications_shop:
    resource: "@SpearDevsSyliusPushNotificationsPlugin/config/routing/shop_routing.yaml"
    prefix: /
```

7. Set env variables:
Example:
```
APP_SCHEME='https'
```
8. Update the database schema:
```
$  bin/console doctrine:schema:update --force
```

9. Finish the instalation by running fixture:
```
$ bin/console sylius:fixtures:load speardevs_push_notification_plugin
```

## Frontend setup
Requires Stimulus framework (https://stimulus.hotwired.dev/handbook/introduction).

### Admin Panel  

1. Run `cp vendor/speardevs/sylius-push-notifications-plugin/tests/Application/assets/controllers/admin/push-notifications-generate_controller.js <path-to-shop-controlers>` to copy admin controller script to your local project.

2. `templates/Admin/PushNotifications/Generate/_formWidget.html.twig` template should automatically use `push-notifications-generate_controller.js` script.

### Storefront

1. Run `cp vendor/speardevs/sylius-push-notifications-plugin/tests/Application/assets/controllers/shop/push-notifications_controller.js <path-to-shop-controlers>` to copy shop controller script to your local project.

2. `templates/Shop/PushNotifications/push_notifications_controls.html.twig` template should automatically use `push-notifications_controller.js` script.

3. Controller params:
    - publicKey - public key for Web Push Protocol,
    - serviceWorkerPath - path to projects service worker file
    - subscribeUrl - url for listening to push notifications

4. Handle push notifications in project's service worker. Example script:

```
self.addEventListener('push', (event) => {
  if (event && event.data) {
    self.pushData = event.data;

    if (self.pushData) {
      const { title, options } = self.pushData.json();

      event.waitUntil(
        self.registration.showNotification(title, options),
      );
    }
  }
});
```

### Basic example for Stimulus installation.
  - Install Stimulus `@hotwired/stimulus` and `@symfony/stimulus-bridge` packages via project's frontend package manager.

  -  Add `bootstrap.js` file to your assets folder:
  ```
  import { startStimulusApp } from '@symfony/stimulus-bridge';
    export const app = startStimulusApp(require.context(
      '@symfony/stimulus-bridge/lazy-controller-loader!./controllers',
      true,
      /\.(j|t)sx?$/,
    ));
  ```

  - Add `controllers.json` file to your assets folder:
  ```
  {
    "controllers": [],
    "entrypoints": []
  }
  ```

  - Import `bootstrap.js` at the end of admin's and shop's `entry.js` files.

  - In admin's and shop's `webpack.config` files, after `.addEntry(...)` line add `.enableStimulusBridge('<path-to-controllers-file>/controllers.json')`

### Customization

#### Available services you can decorate and forms you can extend
```bash
$ bin/console debug:container | grep speardevs_sylius_push_notifications_plugin
```

#### Under the path:
```
admin/push-notification-configurations/
```
in the admin panel, you can set push notification icon.
