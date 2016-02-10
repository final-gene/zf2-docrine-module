<?php
/**
 * File for ObjectExistsFactoryTest class
 *
 * @copyright Copyright (c) 2016, final gene <info@final-gene.de>
 * @author    Frank Giesecke <frank.giesecke@final-gene.de>
 */

namespace FinalGene\DoctrineModuleTest\Unit\Service;

use FinalGene\DoctrineModule\Manager\RepositoryManager;
use FinalGene\DoctrineModule\Validator\ObjectExists;
use FinalGene\DoctrineModule\Validator\ObjectExistsFactory;
use FinalGene\DoctrineModuleTest\Resources\Entity\TestEntity;
use FinalGene\DoctrineModuleTest\Resources\Repository\TestRepository;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Tests for the ObjectExistsFactory class
 *
 * @package FinalGene\DoctrineModuleTest
 */
class ObjectExistsFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers FinalGene\DoctrineModule\Validator\ObjectExistsFactory::createService
     * @uses FinalGene\DoctrineModule\Validator\ObjectExists
     */
    public function testCreateService()
    {
        $factory = new ObjectExistsFactory();
        $factory->setCreationOptions([
            'entity_class' => TestEntity::class,
        ]);

        $this->assertInstanceOf(
            ObjectExists::class,
            $factory->createService($this->getServiceLocator())
        );
    }

    /**
     * @return AbstractPluginManager
     */
    protected function getServiceLocator()
    {
        $repositoryManager = $this->getMock(RepositoryManager::class, ['get'], [], '', false);
        $repositoryManager
            ->expects($this->any())
            ->method('get')
            ->with(TestEntity::class)
            ->willReturn(new TestRepository());

        $parentServiceLocator = $this->getMock(ServiceLocatorInterface::class);
        $parentServiceLocator
            ->expects($this->any())
            ->method('get')
            ->with('RepositoryManager')
            ->willReturn($repositoryManager);

        $serviceLocator = $this->getMockForAbstractClass(ServiceLocatorInterface::class, [], '', true, true, true, ['getServiceLocator']);
        $serviceLocator
            ->expects($this->any())
            ->method('getServiceLocator')
            ->willReturn($parentServiceLocator);

        return $serviceLocator;
    }
}
