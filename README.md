<p align="center">
  <a href="#" target="blank"><img src="https://linku.nl/app/uploads/2020/07/symfony-logo-breed.png" width="320" alt="Symfony Logo" /></a>
</p>


  <p align="center">A progressive PHP framework for building efficient and scalable server-side applications.</p>



## Description
A fullstack shop app with Symfony framework and doctrine orm + webpack encore for handling client side and using twig as template engine.

## Installation

```bash
$ composer install
$ npm i
```

## Running the app

```bash
# development with symfony cli:
$ symfony server:start
# development with php:
$ php -S localhost:8000 -t /public
#running webpack
$ npm run watch
```

## Migrations

```bash
# make new migration
$ php bin/console make:migration
# migrate last migration
$ php bin/console doctrine:migrations:migrate
```

## Test

```bash
# tests
$ php bin/phpunit
```
