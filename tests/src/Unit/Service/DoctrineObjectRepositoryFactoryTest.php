<?php
/**
 * File for DoctrineObjectRepositoryFactoryTest class
 *
 * @copyright Copyright (c) 2015, final gene <info@final-gene.de>
 * @author    Frank Giesecke <frank.giesecke@final-gene.de>
 */

namespace FinalGene\DoctrineModuleTest\Unit\Service;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use FinalGene\DoctrineModule\Manager\EntityManager;
use FinalGene\DoctrineModule\Manager\RepositoryManager;
use FinalGene\DoctrineModule\Service\DoctrineObjectRepositoryFactory;
use FinalGene\DoctrineModuleTest\Resources\Entity\TestEntity;
use FinalGene\DoctrineModuleTest\Unit\Service\DoctrineObjectRepositoryFactoryTest\Repository\TestRepository;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Tests for the DoctrineObjectRepositoryFactory class
 *
 * @package FinalGene\DoctrineModuleTest
 */
class DoctrineObjectRepositoryFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers FinalGene\DoctrineModule\Service\DoctrineObjectRepositoryFactory::canCreateServiceWithName
     */
    public function testCanCreateServiceReturnsFalseIfNoManagerFound()
    {
        $serviceLocator = $this->getServiceLocator();

        $doctrineObjectRepositoryFactory = $this->getMock(DoctrineObjectRepositoryFactory::class, ['getRepository']);
        $doctrineObjectRepositoryFactory
            ->expects($this->once())
            ->method('getRepository')
            ->with($serviceLocator, TestEntity::class)
            ->willReturn(null);
        /** @var DoctrineObjectRepositoryFactory $doctrineObjectRepositoryFactory */

        $this->assertFalse($doctrineObjectRepositoryFactory->canCreateServiceWithName($serviceLocator, '', TestEntity::class));
    }

    /**
     * @covers FinalGene\DoctrineModule\Service\DoctrineObjectRepositoryFactory::canCreateServiceWithName
     */
    public function testCanCreateServiceReturnsTrueIfManagerExists()
    {
        $serviceLocator = $this->getServiceLocator();

        $repository = $this->getMock(ObjectRepository::class);

        $doctrineObjectRepositoryFactory = $this->getMock(DoctrineObjectRepositoryFactory::class, ['getRepository']);
        $doctrineObjectRepositoryFactory
            ->expects($this->once())
            ->method('getRepository')
            ->with($serviceLocator, TestEntity::class)
            ->willReturn($repository);
        /** @var DoctrineObjectRepositoryFactory $doctrineObjectRepositoryFactory */

        $this->assertTrue($doctrineObjectRepositoryFactory->canCreateServiceWithName($serviceLocator, '', TestEntity::class));
    }

    /**
     * @covers FinalGene\DoctrineModule\Service\DoctrineObjectRepositoryFactory::createServiceWithName
     */
    public function testCreateServiceReturnsNull()
    {
        $serviceLocator = $this->getServiceLocator();

        $doctrineObjectRepositoryFactory = $this->getMock(DoctrineObjectRepositoryFactory::class, ['getRepository']);
        $doctrineObjectRepositoryFactory
            ->expects($this->once())
            ->method('getRepository')
            ->with($serviceLocator, TestEntity::class)
            ->willReturn(null);
        /** @var DoctrineObjectRepositoryFactory $doctrineObjectRepositoryFactory */

        $this->assertNull($doctrineObjectRepositoryFactory->createServiceWithName($serviceLocator, '', TestEntity::class));
    }

    /**
     * @covers FinalGene\DoctrineModule\Service\DoctrineObjectRepositoryFactory::createServiceWithName
     */
    public function testCreateServiceReturnsRepository()
    {
        $serviceLocator = $this->getServiceLocator();

        $repository = $this->getMock(ObjectRepository::class);

        $doctrineObjectRepositoryFactory = $this->getMock(DoctrineObjectRepositoryFactory::class, ['getRepository']);
        $doctrineObjectRepositoryFactory
            ->expects($this->once())
            ->method('getRepository')
            ->with($serviceLocator, TestEntity::class)
            ->willReturn($repository);
        /** @var DoctrineObjectRepositoryFactory $doctrineObjectRepositoryFactory */

        $this->assertInstanceOf(ObjectRepository::class, $doctrineObjectRepositoryFactory->createServiceWithName($serviceLocator, '', TestEntity::class));
    }

    public function testGetRepository()
    {
        $serviceLocator = $this->getServiceLocator();

        $doctrineObjectRepositoryFactory = new DoctrineObjectRepositoryFactory();

        $getRepository = $this->getMethod('getRepository');
        $getRepository->invokeArgs($doctrineObjectRepositoryFactory, [$serviceLocator, TestEntity::class]);
    }

//    /**
//     * Tests if the factory creates a repository via the entitymanager
//     *
//     * @return void
//     */
//    public function testCreateService()
//    {
//        $parentServiceLocatorMock = $this->getMock(ServiceLocatorInterface::class);
//
//        $serviceLocatorMock = $this->getMock(RepositoryManager::class);
//        $serviceLocatorMock
//            ->expects($this->any())
//            ->method('getServiceLocator')
//            ->willReturn($parentServiceLocatorMock);
//
//        $doctrineEntityManagerMock = $this->getMock(
//            EntityManagerInterface::class,
//            [],
//            [],
//            '',
//            false
//        );
//        $doctrineEntityManagerMock
//            ->expects($this->once())
//            ->method('getRepository')
//            ->will(
//                $this->returnValueMap([
//                    [
//                        TestEntity::class,
//                        new TestRepository()
//                    ]
//                ])
//            );
//
//        $entityManagerMock = $this->getMock(EntityManager::class);
//        $entityManagerMock
//            ->expects($this->any())
//            ->method('get')
//            ->willReturn($doctrineEntityManagerMock);
//
//        $parentServiceLocatorMock
//            ->expects($this->any())
//            ->method('get')
//            ->with('EntityManager')
//            ->willReturn($entityManagerMock);
//
//        $this->assertInstanceOf(
//            TestRepository::class,
//            $this->doctrineObjectRepositoryFactory->createServiceWithName(
//                $serviceLocatorMock,
//                '',
//                TestEntity::class
//            )
//        );
//    }

    /**
     * @return AbstractPluginManager
     */
    protected function getServiceLocator()
    {
        $doctrineEntityManager = $this->getMock(
            EntityManagerInterface::class,
            [],
            [],
            '',
            false
        );
        $doctrineEntityManager
            ->expects($this->once())
            ->method('getRepository')
            ->will(
                $this->returnValueMap([
                    [
                        TestEntity::class,
                        new TestRepository()
                    ]
                ])
            );

        $entityManager = $this->getMock(EntityManager::class);
        $entityManager
            ->expects($this->any())
            ->method('get')
            ->willReturn($doctrineEntityManager);

        $parentServiceLocator = $this->getMock(ServiceLocatorInterface::class);
        $parentServiceLocator
            ->expects($this->any())
            ->method('get')
            ->with('EntityManager')
            ->willReturn($entityManager);

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
        $class = new \ReflectionClass(DoctrineObjectRepositoryFactory::class);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }
}
