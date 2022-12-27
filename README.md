# Investify - Stock trading website

## Main features
* User registration
* Current stock prices and price changes
* Buy, sell and short stocks
* Transfering stocks between users
* Transaction history

## Requirements
* PHP version: 7.4 
* MySQL version: 8.0.31-0ubuntu0.22.04.1 
* Composer version ^2.4.4

## Used packages
* [Carbon](https://carbon.nesbot.com/)
* [Twig](https://twig.symfony.com/)
* [Dotenv](https://packagist.org/packages/vlucas/phpdotenv)
* [Fast route](https://packagist.org/packages/nikic/fast-route)
* [Finnhub client](https://packagist.org/packages/finnhub/client)
* [Dbal](https://packagist.org/packages/doctrine/dbal)
* [PHP-DI](https://php-di.org/)

## Installation
1. Clone this repository
2. Install composer
3. Recreate an account on https://finnhub.io/ to get the API key
4. Rename ".env.example" to ".env" and enter the correct information in the parenthesis
5. Import the "schema.sql"
6. You can run the development website by typing in terminal: php -S localhost:8000


