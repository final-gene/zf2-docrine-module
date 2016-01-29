<?php
/**
 * File for EntityManagerFactoryTest class
 *
 * @copyright Copyright (c) 2016, final gene <info@final-gene.de>
 * @author    Frank Giesecke <frank.giesecke@final-gene.de>
 */

namespace FinalGene\DoctrineModuleTest\Unit\Manager;

use FinalGene\DoctrineModule\Manager\EntityManager;
use FinalGene\DoctrineModule\Manager\EntityManagerFactory;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class EntityManagerFactoryTest
 *
 * @package FinalGene\DoctrineModuleTest\Unit\Manager
 */
class EntityManagerFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers FinalGene\DoctrineModule\Manager\EntityManagerFactory::createService
     */
    public function testCreateService()
    {
        $serviceLocator = $this->getMockForAbstractClass(ServiceLocatorInterface::class);
        /** @var ServiceLocatorInterface $serviceLocator */

        $factory = new EntityManagerFactory();

        $this->assertInstanceOf(
            EntityManager::class,
            $factory->createService($serviceLocator)
        );
    }
}
