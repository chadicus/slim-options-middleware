# Slim OPTIONS Middleware

Middleware to add an OPTIONS route to existing routes.

[![Build Status](https://travis-ci.com/subjective-php/slim-options-middlware.svg?branch=master)](https://travis-ci.com/subjective-php/slim-options-middlware)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/subjective-php/slim-options-middlware/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/subjective-php/slim-options-middlware/?branch=master)
[![Coverage Status](https://coveralls.io/repos/github/subjective-php/slim-options-middlware/badge.svg?branch=master)](https://coveralls.io/github/subjective-php/slim-options-middlware?branch=master)

[![Latest Stable Version](https://poser.pugx.org/subjective-php/slim-options-middlware/v/stable)](https://packagist.org/packages/subjective-php/slim-options-middlware)
[![Latest Unstable Version](https://poser.pugx.org/subjective-php/slim-options-middlware/v/unstable)](https://packagist.org/packages/subjective-php/slim-options-middlware)
[![License](https://poser.pugx.org/subjective-php/slim-options-middlware/license)](https://packagist.org/packages/subjective-php/slim-options-middlware)

[![Total Downloads](https://poser.pugx.org/subjective-php/slim-options-middlware/downloads)](https://packagist.org/packages/subjective-php/slim-options-middlware)
[![Daily Downloads](https://poser.pugx.org/subjective-php/slim-options-middlware/d/daily)](https://packagist.org/packages/subjective-php/slim-options-middlware)
[![Monthly Downloads](https://poser.pugx.org/subjective-php/slim-options-middlware/d/monthly)](https://packagist.org/packages/subjective-php/slim-options-middlware)

## Requirements

Requires PHP 7.0 (or later).

## Composer
To add the library as a local, per-project dependency use [Composer](http://getcomposer.org)! Simply add a dependency on `subjective-php/slim-options-middlware` to your project's `composer.json` file such as:

```sh
composer require subjective-php/slim-options-middlware
```

## Contact
Developers may be contacted at:

 * [Pull Requests](https://github.com/subjective-php/slim-options-middlware/pulls)
 * [Issues](https://github.com/subjective-php/slim-options-middlware/issues)

## Project Build
With a checkout of the code get [Composer](http://getcomposer.org) in your PATH and run:

```sh
composer install
./vendor/bin/phpunit
./vendor/bin/phpcs
```
## Slim 3 Example
```php
require __DIR__ . '/vendor/autoload.php';

use SubjectivePHP\Slim\Middleware;

// This Slim setting is required for the middleware to work
$app = new Slim\App([
    "settings"  => [
        "determineRouteBeforeAppMiddleware" => true,
    ]
]);

// create the middlware
$optionsMiddleware = new Middleware\OptionsMiddleware('*', ['Authorization', 'Content-Type']);

$app->map(['GET', 'POST'], 'foos', function ($request, $response, $args) {
    return $response;
};

$app->add($optionsMiddleware);

$app->run();
```

#### Send an OPTIONS request to the API
```
curl -i -X OPTIONS http://example.org/foos
```
#### Response will be similar to
```
HTTP/1.1 200 OK
Access-Control-Allow-Headers: Authorization, Content-Type
Access-Control-Allow-Methods: GET, POST, OPTIONS
Access-Control-Allow-Origin: *
Content-Type: text/html; charset=UTF-8
Date: Mon, 22 Apr 2019 12:45:18 GMT
Server: Apache/2.4.18 (Ubuntu)
Content-Length: 0
Connection: keep-alive
```




