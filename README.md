# A PHP client for OneVision Amendo

[OneVision Amendo](https://www.onevision.com/solutions/image-editing/amendo/) is automated image enhancement software.
This PHP client library uses its SOAP API.

This is not an official library supplied by the OneVision vendor. 
It has been developed for the WoodWing Assets project at the German [SPIEGEL Gruppe](https://www.spiegelgruppe.de), 2020.

## Installation

Use [Composer](https://getcomposer.org/) to add this library your project’s composer.json file:

```
$ composer require der-spiegel/amendo-client
```

## Developing this library

If you want to help developing this library, Here’s how to get started (Docker required):

### Install dependencies using Composer

```
$ docker run --rm --interactive --tty \
  --volume $PWD:/app \
  --volume ${COMPOSER_HOME:-$HOME/.composer}:/tmp \
  composer install
$ docker run --rm --interactive --tty \
  --volume $PWD:/app \
  --volume ${COMPOSER_HOME:-$HOME/.composer}:/tmp \
  composer require monolog/monolog
```

### Copy and edit the example script

`$ cp UsageExample.php MyExample.php`

Edit your copy, setting the correct URL:

```php
$amendoConfig = new AmendoConfig('http://amendo.example.com/');
```

### Then run your copy

```
$ docker run -it --rm --name amendo-client-example \
  --volume "$PWD":/usr/src/myapp --workdir /usr/src/myapp \
  php:7.4-cli php MyExample.php
```

## Authors

* [Tim Strehle](https://github.com/tistre) - https://twitter.com/tistre

## License

This library is licensed under the MIT License - see the `LICENSE` file for details.
