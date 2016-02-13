# Throttle (v0.3.3)
Ban identifier after certain amount of requests in a given timeframe.

[![Build Status](https://travis-ci.org/sideshowcecil/throttle.png)](https://travis-ci.org/sideshowcecil/throttle)

## Installation

The suggested installation method is via [composer](https://getcomposer.org/):

```sh
php composer.phar require "sideshow_bob/throttle"
```


## Usage
Basic usage of the `Throttle` class to ban an identifier.

```php
use Websoftwares\Throttle, Websoftwares\Storage\Memcached, Monolog\Logger;

// ip
$identifier = $_SERVER["REMOTE_ADDR"];
// instantiate class
$throttle = new \sideshow_bob\Throttlenew \sideshow_bob\Storage\Memcached());

if($throttle->validate($identifier)) {
	// success proceed
} else {
	// banned
}

```

## Storage
Included is a `Memcached` example however it is very easy to use some other storage system
just implement the _StorageInterface_ and inject that object into the `Throttle` constructor.

####_Caution_####
Whatever storage system u decide to use,
don not store the failed request data into your database,
this could lead to a DDOS attack and take your database down.

## Options
You can override the default options by instantiating a `Throttle` class and pass in an _array_ as the third argument.

```php
$options = [
	"banned" => 10,     // ban identifier after 10 attempts. (default 5)
	"logged" => 20,     // log identifier after 20 attempts. (default 10)
	"timespan" => 60,   // the timespan for the duration of the ban. (default 86400)
];

// Instantiate class
$throttle = new \sideshow_bob\Throttlenew \sideshow_bob\Storage\Memcached(), $options);

```

## Logger
Any logger library that implements the [PSR-3](https://github.com/php-fig/log) _LoggerInterface_ should work,
just create your Logger object and inject it into the `Throttle` constructor.
For example the excellent logging library [Monolog](https://github.com/seldaek/monolog).

## Other Methods

### reset();
This will remove the identifier from the storage.
```php
$throttle->reset($identifier);
```

### remaining();
This will return an integer that is the remaining attempt(s) available before identifier gets banned.
```php
$throttle->remaining($identifier);
```

## Testing
The test folder contains all tests.

## Acknowledgement
Forked from [websoftwares/throttle](https://github.com/websoftwares/Throttle).
