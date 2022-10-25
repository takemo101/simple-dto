#### for php ####
.PHONY: start
start:
	docker-compose up -d php

.PHONY: shell
shell:
	docker-compose exec php bash

.PHONY: stop
stop:
	docker-compose stop php

.PHONY: remove
remove:
	docker-compose rm php

.PHONY: install
install:
	docker-compose run --rm composer install

.PHONY: update
update:
	docker-compose run --rm composer update

.PHONY: autoload
autoload:
	docker-compose run --rm composer dump-autoload

.PHONY: test
test:
	docker-compose run --rm php ./vendor/bin/phpunit

.PHONY: phpstan
phpstan:
	docker-compose run --rm phpstan analyse

.PHONY: analyze
analyze:
	docker-compose run --rm php ./vendor/bin/phpstan analyse

#### for docker-compose ####

.PHONY: setup
setup:
	cp .example.env .env
