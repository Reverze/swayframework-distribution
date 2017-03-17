# Distribution component
This component helps to manage swayframework and vendor packages.
It also provides a feature to manage configuration around packages and app.

## Installation

*This package is bundled with swayframework*.
 
```
composer require rev/swayframework-distribution
```

This will install package at latest version.

## Usage

```php

<?php

use Sway\Distribution\FrameworkDistribution;

/**
* Points to application root directory.
 */
$applicationDirectoryRootPath = dirname(__DIR__);

/**
* Inits framework distribution.
 */
$frameworkDistribution = new FrameworkDistribution($applicationDirectoryRootPath);


?>
```

Please remember that subdirectory *tmp* must exists in your app directory.
It will not be created if not exists.

## Built-in services
Distribution component comes with built-in services (of course if service component
is available).

List of all built-in services:

* **Service:** 'distribution_storage' <br>
  **Class:** Sway\Distribution\Storage\StorageDriver <br>
  **Description:** Storage driver - vendor's packages and application cache
  
* **Service:** 'distribution_class_founder' <br>
  **Class:** Sway\Distribution\Mapping\ClassFounder <br>
  **Description**: Application class map

* **Service:** 'distribution_extension_manager' <br>
  **Class:** Sway\Distribution\Extension\ExtensionManager <br>
  **Description**: 'sf-package' libraries manager


For defaults, built-in services are not registered. If framework kernel is initialized
and service and parameter component are available, you can call method *initDistribution*
to register built-in services.

```php
$frameworkDistribution->initDistribution();
```

You can also initialize a interface to framework as service (service: *framework*):

```php
$frameworkDistribution->initializeFrameworkService($array_with_parameters);
```


