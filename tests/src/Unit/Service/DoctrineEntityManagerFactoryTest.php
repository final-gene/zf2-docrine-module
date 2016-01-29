<?php
/**
 * File for DoctrineEntityManagerFactoryTest class
 *
 * @copyright Copyright (c) 2016, final gene <info@final-gene.de>
 * @author    Frank Giesecke <frank.giesecke@final-gene.de>
 */

namespace FinalGene\DoctrineModuleTest\Unit\Service;

use Doctrine\ORM\EntityManagerInterface;
use FinalGene\DoctrineModule\Service\DoctrineEntityManagerFactory;
use FinalGene\DoctrineModuleTest\Resources\Entity\TestEntity;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Tests for the DoctrineEntityManagerFactoryTest class
 *
 * @package FinalGene\RepositoryManagerModuleTest
 */
class DoctrineEntityManagerFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers FinalGene\DoctrineModule\Service\DoctrineEntityManagerFactory::canCreateServiceWithName
     */
    public function testCanCreateServiceReturnsFalseIfNoManagerFound()
    {
        $serviceLocator = $this->getServiceLocator();

        $doctrineObjectRepositoryFactory = $this->getMock(DoctrineEntityManagerFactory::class, ['getManager']);
        $doctrineObjectRepositoryFactory
            ->expects($this->once())
            ->method('getManager')
            ->with($serviceLocator, TestEntity::class)
            ->willReturn(null);
        /** @var DoctrineEntityManagerFactory $doctrineObjectRepositoryFactory */

        $this->assertFalse($doctrineObjectRepositoryFactory->canCreateServiceWithName($serviceLocator, '', TestEntity::class));
    }

    /**
     * @covers FinalGene\DoctrineModule\Service\DoctrineEntityManagerFactory::canCreateServiceWithName
     */
    public function testCanCreateServiceReturnsTrueIfManagerExists()
    {
        $serviceLocator = $this->getServiceLocator();

        $entityManager = $this->getMock(EntityManagerInterface::class);

        $doctrineObjectRepositoryFactory = $this->getMock(DoctrineEntityManagerFactory::class, ['getManager']);
        $doctrineObjectRepositoryFactory
            ->expects($this->once())
            ->method('getManager')
            ->with($serviceLocator, TestEntity::class)
            ->willReturn($entityManager);
        /** @var DoctrineEntityManagerFactory $doctrineObjectRepositoryFactory */

        $this->assertTrue($doctrineObjectRepositoryFactory->canCreateServiceWithName($serviceLocator, '', TestEntity::class));
    }

    /**
     * @covers FinalGene\DoctrineModule\Service\DoctrineEntityManagerFactory::createServiceWithName
     */
    public function testCreateServiceReturnsNull()
    {
        $serviceLocator = $this->getServiceLocator();

        $doctrineObjectRepositoryFactory = $this->getMock(DoctrineEntityManagerFactory::class, ['getManager']);
        $doctrineObjectRepositoryFactory
            ->expects($this->once())
            ->method('getManager')
            ->with($serviceLocator, TestEntity::class)
            ->willReturn(null);
        /** @var DoctrineEntityManagerFactory $doctrineObjectRepositoryFactory */

        $this->assertNull($doctrineObjectRepositoryFactory->createServiceWithName($serviceLocator, '', TestEntity::class));
    }

    /**
     * @covers FinalGene\DoctrineModule\Service\DoctrineEntityManagerFactory::createServiceWithName
     */
    public function testCreateServiceReturnsEntityManager()
    {
        $serviceLocator = $this->getServiceLocator();

        $entityManager = $this->getMock(EntityManagerInterface::class);

        $doctrineObjectRepositoryFactory = $this->getMock(DoctrineEntityManagerFactory::class, ['getManager']);
        $doctrineObjectRepositoryFactory
            ->expects($this->once())
            ->method('getManager')
            ->with($serviceLocator, TestEntity::class)
            ->willReturn($entityManager);
        /** @var DoctrineEntityManagerFactory $doctrineObjectRepositoryFactory */

        $this->assertInstanceOf(EntityManagerInterface::class, $doctrineObjectRepositoryFactory->createServiceWithName($serviceLocator, '', TestEntity::class));
    }

    /**
     * @covers FinalGene\DoctrineModule\Service\DoctrineEntityManagerFactory::getManager
     * @expectedException \FinalGene\DoctrineModule\Exception\ConfigurationException
     */
    public function testGetManagerThrowsExceptionIfNoConfigExists()
    {
        $serviceLocator = $this->getServiceLocator([]);

        $doctrineObjectRepositoryFactory = new DoctrineEntityManagerFactory();

        $getManager = $this->getMethod('getManager');
        $getManager->invokeArgs($doctrineObjectRepositoryFactory, [$serviceLocator, TestEntity::class]);
    }

    /**
     * @covers FinalGene\DoctrineModule\Service\DoctrineEntityManagerFactory::getManager
     */
    public function testGetManagerReturnsNullIfNoManagerWasFound()
    {
        $serviceLocator = $this->getServiceLocator([
            'doctrine' => [
                'driver' => [
                    'orm_default' => [
                        'drivers' => [
                            'foo' => 'bar',
                        ],
                    ],
                ],
            ],
        ]);

        $doctrineObjectRepositoryFactory = new DoctrineEntityManagerFactory();

        $getManager = $this->getMethod('getManager');
        $result = $getManager->invokeArgs($doctrineObjectRepositoryFactory, [$serviceLocator, TestEntity::class]);

        $this->assertNull($result);
    }

    /**
     * @covers FinalGene\DoctrineModule\Service\DoctrineEntityManagerFactory::getManager
     */
    public function testGetManagerReturnsEntityManagerMatchedByNamespaceAsKey()
    {
        $config = [
            'doctrine' => [
                'driver' => [
                    'foo' => [
                        'paths' => [
                            __DIR__ . '/../../Resources/Entity',
                        ],
                    ],
                    'orm_default' => [
                        'drivers' => [
                            'FinalGene\DoctrineModuleTest\Resources\Entity' => 'bar',
                        ],
                    ],
                ],
            ],
        ];

        $entityManager = $this->getMock(EntityManagerInterface::class);

        $parentServiceLocator = $this->getMock(ServiceLocatorInterface::class);
        $parentServiceLocator
            ->expects($this->any())
            ->method('get')
            ->withConsecutive(
                ['config'],
                ['doctrine.entitymanager.orm_default']
            )
            ->willReturnOnConsecutiveCalls(
                $config,
                $entityManager,
                $config,
                $entityManager
            );

        $serviceLocator = $this->getMockForAbstractClass(AbstractPluginManager::class, [], '', true, true, true, ['getServiceLocator']);
        $serviceLocator
            ->expects($this->any())
            ->method('getServiceLocator')
            ->willReturn($parentServiceLocator);

        $doctrineObjectRepositoryFactory = new DoctrineEntityManagerFactory();

        $getManager = $this->getMethod('getManager');
        $result = $getManager->invokeArgs($doctrineObjectRepositoryFactory, [$serviceLocator, TestEntity::class]);

        $this->assertInstanceOf(EntityManagerInterface::class, $result);
    }

    /**
     * @covers FinalGene\DoctrineModule\Service\DoctrineEntityManagerFactory::getManager
     */
    public function testGetManagerReturnsEntityManagerMatchedByPath()
    {
        $config = [
            'doctrine' => [
                'driver' => [
                    'bar' => [
                        'paths' => [
                            __DIR__ . '/../../Resources/Entity',
                        ],
                    ],
                    'orm_default' => [
                        'drivers' => [
                            'foo' => 'bar',
                        ],
                    ],
                ],
            ],
        ];

        $entityManager = $this->getMock(EntityManagerInterface::class);

        $parentServiceLocator = $this->getMock(ServiceLocatorInterface::class);
        $parentServiceLocator
            ->expects($this->any())
            ->method('get')
            ->withConsecutive(
                ['config'],
                ['doctrine.entitymanager.orm_default']
            )
            ->willReturnOnConsecutiveCalls(
                $config,
                $entityManager,
                $config,
                $entityManager
            );

        $serviceLocator = $this->getMockForAbstractClass(AbstractPluginManager::class, [], '', true, true, true, ['getServiceLocator']);
        $serviceLocator
            ->expects($this->any())
            ->method('getServiceLocator')
            ->willReturn($parentServiceLocator);

        $doctrineObjectRepositoryFactory = new DoctrineEntityManagerFactory();

        $getManager = $this->getMethod('getManager');
        $result = $getManager->invokeArgs($doctrineObjectRepositoryFactory, [$serviceLocator, TestEntity::class]);

        $this->assertInstanceOf(EntityManagerInterface::class, $result);
    }

    /**
     * @param array $config
     * @return AbstractPluginManager
     */
    protected function getServiceLocator(array $config = [])
    {
        $parentServiceLocator = $this->getMock(ServiceLocatorInterface::class);
        $parentServiceLocator
            ->expects($this->any())
            ->method('get')
            ->with('config')
            ->willReturn($config);

        $serviceLocator = $this->getMockForAbstractClass(AbstractPluginManager::class, [], '', true, true, true, ['getServiceLocator']);
        $serviceLocator
            ->expects($this->any())
            ->method('getServiceLocator')
            ->willReturn($parentServiceLocator);

        return $serviceLocator;
    }

    /**
     * @param $name
     *
     * @return \ReflectionMethod
     */
    protected function getMethod($name)
    {
        $class = new \ReflectionClass(DoctrineEntityManagerFactory::class);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }
}
