#!/usr/bin/env bash

# composer update
composer self-update
composer install --no-interaction --prefer-source

# enable php modules
if [ -f ~/.phpenv/versions/$(phpenv version-name)/etc/php.ini ]; then
    echo "extension = memcached.so" >> ~/.phpenv/versions/$(phpenv version-name)/etc/php.ini
    echo "extension = redis.so" >> ~/.phpenv/versions/$(phpenv version-name)/etc/php.ini
fi
