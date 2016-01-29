<?php
/**
 * File for RepositoryManagerFactoryTest class
 *
 * @copyright Copyright (c) 2016, final gene <info@final-gene.de>
 * @author    Frank Giesecke <frank.giesecke@final-gene.de>
 */

namespace FinalGene\DoctrineModuleTest\Unit\Manager;

use FinalGene\DoctrineModule\Manager\RepositoryManager;
use FinalGene\DoctrineModule\Manager\RepositoryManagerFactory;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class RepositoryManagerFactoryTest
 *
 * @package FinalGene\DoctrineModuleTest\Unit\Manager
 */
class RepositoryManagerFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers FinalGene\DoctrineModule\Manager\RepositoryManagerFactory::createService
     */
    public function testCreateService()
    {
        $serviceLocator = $this->getMockForAbstractClass(ServiceLocatorInterface::class);
        /** @var ServiceLocatorInterface $serviceLocator */

        $factory = new RepositoryManagerFactory();

        $this->assertInstanceOf(
            RepositoryManager::class,
            $factory->createService($serviceLocator)
        );
    }
}
