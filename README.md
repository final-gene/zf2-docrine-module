# Doctrine module
This module provides some help full extension for the ZF2 doctrine module. 

## Installation
After composer is available in your path you are ready to install this module. 

```
$ composer require final-gene/doctrine-module
```

Then add `FinalGene\DoctrineModule` to your application config file.

## Content

### Entity Manager

#### Background
In ZF2, we retrieve our entity manager like this (example is from a factory):

```php
<?php
// ...
public function createService(ServiceLocatorInterface $serviceManager)
{
    // like this
    $entityManager = $serviceManager->get('Doctrine\\ORM\\EntityManager');
    // or that
    $entityManager = $serviceManager->get('doctrine.entitymanager.orm_default');
}
```

The downside of this is, that you have to know which is the name of your configured manager/connection.
If you deal with more than one connection or be part of a multi module project, you are not able to know which
connection or entity manager belongs to your entities.

#### Usage

With the use of this module, the above code transforms into the following:

```php
<?php
// ...
public function createService(ServiceLocatorInterface $serviceManager)
{
    $someEntityManager = $serviceManager->get('EntityManager')->get('SomeEntity');
}
```

Now you get a entity manager depending on your loaded modules and their configuration.

If no one configures the DoctrineModule (or the manager within), an abstract factory is called which just load the
default entity manager of doctrine.

#### Configuration

Simply configure your doctrine connections a described in [DoctrineORMModule Documentation](https://github.com/doctrine/DoctrineORMModule/blob/master/docs/configuration.md#how-to-use-two-connections).

### Repository Manager

#### Background

In ZF2, we retrieve our repositories like this (example is from a factory):

```php
<?php
public function createService(ServiceLocatorInterface $serviceManager)
{
    $entityManager = $serviceManager->get('Doctrine\\ORM\\EntityManager');
    $someRepository = $entityManager->getRepository('SomeEntity');
}
```

This has multiple downsides:

1. changing the repositories at runtime is hard because you can't configure the behaviour of the entity manager / entity
manager's `getRepository` method, so you have to invent a "proxy" which then has additional logic when to switch between
your different repositories
2. hard to test: to use an alternative repository you have to mock the getRepository function of the mighty entity
manager
3. hidden dependency: we don't request a dependency from the service manager but from the entity manager
4. no easy using of a factory class to create your repository

#### Usage

With the use of this module, the above code transforms into the following:

```php
<?php
// ...
public function createService(ServiceLocatorInterface $serviceManager)
{
    $someRepository = $serviceManager->get('RepositoryManager')->get('SomeEntity');
}
```

Now you get a repository depending on your loaded modules and their repository manager configuration.

If no one configures the RepositoryManagerModule, an abstract factory is called which just proxies the request to
doctrines entity manager `getRepository` function.

#### Configuration

##### Configuration Key
The configuration key is `repositories`. Sub-Keys are the same as in every service manager (invokables, factories, ...).

##### Examples

In your module class via `getConfig`:

```php
<?php
// ...
public function getConfig()
{
    return [
        'repositories' => [
            'factories' => [
                // your repository factories goes here
                // format: Entity-Name => Repository-Factory.
            ],
        ],
    ];
}
```

or via `FinalGene\RepositoryManagerModule\ModuleManager\Feature\RepositoryProviderInterface` (method
`getRepositoryConfig`)

```php
<?php
// ...
public function getRepositoryConfig()
{
    return [
        'factories' => [
            // your repository factories goes here
        ],
    ];
}
```

or via config/autoload/repository-manager.global.config.php

```php
return [
    'repositories' => [
        'factories' => [
            // your repository factories goes here
            // format: Entity-Name => Repository-Factory.
        ],
    ],
];
```

