# RevolutPHPBundle 

Symfony Bundle for RevolutPHP - https://github.com/sverraest/revolut-php

[![Build Status](https://travis-ci.org/sverraest/revolut-php-bundle.svg?branch=master)](https://travis-ci.org/sverraest/revolut-php-bundle)
[![codecov](https://codecov.io/gh/sverraest/revolut-php-bundle/branch/master/graph/badge.svg)](https://codecov.io/gh/sverraest/revolut-php-bundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/sverraest/revolut-php-bundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/sverraest/revolut-php-bundle/?branch=master)
[![Maintainability](https://api.codeclimate.com/v1/badges/be29d03a42b4cfcc449e/maintainability)](https://codeclimate.com/github/sverraest/revolut-php-bundle/maintainability)

## Installation

This bundle is for Symfony3 and higher.

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require sverraest/revolut-php-bundle
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Sverraest\RevolutPHPBundle\SverraestRevolutPHPBundle(),
        );

        // ...
    }

    // ...
}
```

### Step 3: Add configuration

You must specify the following configuration

```yaml
sverraest_revolut_php:
    api_key: 'foo'
    mode: 'production' # options are production or sandbox
```

### Step 4: Get the RevolutPHP Service

You can get an instance of the public RevolutPHP Service or autowire it.
```php
// src/AppBundle/Controller/AcmeController.php
...

$revolut = $this->get('revolut_php.client');
print_r($revolut->accounts->all())

...

```

Or via autowiring:

```php
// src/AppBundle/Service/AcmeService.php
...

public function __construct(RevolutPHP\Client $client) 
{
    $this->client = $client;
}

...
```

## Documentation

> Read the [documentation](Resources/doc/index.md) for this bundle

