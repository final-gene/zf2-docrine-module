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

