<p align="center">
    <a href="https://sylius.com" target="_blank">
        <img src="https://demo.sylius.com/assets/shop/img/logo.png" />
    </a>
</p>

<h1 align="center">Plugin Skeleton</h1>

<p align="center">Skeleton for starting Sylius plugins.</p>

## Documentation

For a comprehensive guide on Sylius Plugins development please go to Sylius documentation,
there you will find the <a href="https://docs.sylius.com/en/latest/plugin-development-guide/index.html">Plugin Development Guide</a>, that is full of examples.

## Quickstart Installation

### Traditional

1. Run `composer create-project sylius/plugin-skeleton ProjectName`.

2. From the plugin skeleton root directory, run the following commands:

    ```bash
    $ (cd tests/Application && yarn install)
    $ (cd tests/Application && yarn build)
    $ (cd tests/Application && APP_ENV=test bin/console assets:install public)
    
    $ (cd tests/Application && APP_ENV=test bin/console doctrine:database:create)
    $ (cd tests/Application && APP_ENV=test bin/console doctrine:schema:create)
    ```

To be able to set up a plugin's database, remember to configure you database credentials in `tests/Application/.env` and `tests/Application/.env.test`.

### Docker

1. Execute `docker compose up -d`

2. Initialize plugin `docker compose exec app make init`

3. See your browser `open localhost`

## Usage

### Running plugin tests

  - PHPUnit

    ```bash
    vendor/bin/phpunit
    ```

  - PHPSpec

    ```bash
    vendor/bin/phpspec run
    ```

  - Behat (non-JS scenarios)

    ```bash
    vendor/bin/behat --strict --tags="~@javascript"
    ```

  - Behat (JS scenarios)
 
    1. [Install Symfony CLI command](https://symfony.com/download).
 
    2. Start Headless Chrome:
    
      ```bash
      google-chrome-stable --enable-automation --disable-background-networking --no-default-browser-check --no-first-run --disable-popup-blocking --disable-default-apps --allow-insecure-localhost --disable-translate --disable-extensions --no-sandbox --enable-features=Metal --headless --remote-debugging-port=9222 --window-size=2880,1800 --proxy-server='direct://' --proxy-bypass-list='*' http://127.0.0.1
      ```
    
    3. Install SSL certificates (only once needed) and run test application's webserver on `127.0.0.1:8080`:
    
      ```bash
      symfony server:ca:install
      APP_ENV=test symfony server:start --port=8080 --dir=tests/Application/public --daemon
      ```
    
    4. Run Behat:
    
      ```bash
      vendor/bin/behat --strict --tags="@javascript"
      ```
    
  - Static Analysis
  
    - Psalm
    
      ```bash
      vendor/bin/psalm
      ```
      
    - PHPStan
    
      ```bash
      vendor/bin/phpstan analyse -c phpstan.neon -l max src/  
      ```

  - Coding Standard
  
    ```bash
    vendor/bin/ecs check
    ```

### Opening Sylius with your plugin

- Using `test` environment:

    ```bash
    (cd tests/Application && APP_ENV=test bin/console sylius:fixtures:load)
    (cd tests/Application && APP_ENV=test symfony server:start -d)
    ```
    
- Using `dev` environment:

    ```bash
    (cd tests/Application && APP_ENV=dev bin/console sylius:fixtures:load)
    (cd tests/Application && APP_ENV=dev symfony server:start -d)
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
