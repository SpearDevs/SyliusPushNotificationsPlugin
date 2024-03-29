ARGUMENTS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))

start:
	(cd tests/Application && docker compose up -d)
	(cd tests/Application && APP_ENV=dev symfony server:start -d)

stop:
	(cd tests/Application && docker compose down)
	(cd tests/Application && APP_ENV=dev symfony server:stop)

ecs:
	vendor/bin/ecs check "$(ARGUMENTS)"

ecs-fix:
	vendor/bin/ecs check "$(ARGUMENTS)" --fix

phpunit:
	vendor/bin/phpunit

phpspec:
	vendor/bin/phpspec run --ansi --no-interaction -f dot

phpstan:
	vendor/bin/phpstan analyse

psalm:
	vendor/bin/psalm

behat-js:
	APP_ENV=test vendor/bin/behat --colors --strict --no-interaction -vvv -f progress

install:
	composer install --no-interaction --no-scripts

backend:
	tests/Application/bin/console sylius:install --no-interaction
	tests/Application/bin/console sylius:fixtures:load default --no-interaction

frontend:
	(cd tests/Application && yarn install --pure-lockfile)
	(cd tests/Application && GULP_ENV=prod yarn build)

behat:
	APP_ENV=test vendor/bin/behat --colors --strict --no-interaction -vvv -f progress

init: install backend frontend

ci: init phpstan psalm phpunit phpspec behat

integration: init phpunit behat

static: install phpspec phpstan psalm

