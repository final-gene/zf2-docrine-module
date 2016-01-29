<?php
/**
 * This file is part of the doctrine-module.php project.
 *
 * @copyright Copyright (c) 2016, final gene <info@final-gene.de>
 * @author    Frank Giesecke <frank.giesecke@final-gene.de>
 */

namespace FinalGene\DoctrineModuleTest\Unit;

use FinalGene\DoctrineModule\Module;
use FinalGene\DoctrineModule\ModuleManager\Feature\EntityManagerProviderInterface;
use FinalGene\DoctrineModule\ModuleManager\Feature\RepositoryProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\DependencyIndicatorInterface;
use Zend\ModuleManager\Feature\InitProviderInterface;
use Zend\ModuleManager\Listener\ServiceListenerInterface;
use Zend\ModuleManager\ModuleManagerInterface;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ModuleTest
 *
 * @package FinalGene\DoctrineModuleTest\Unit
 */
class ModuleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Make sure module config can be serialized.
     *
     * Make sure module config can be serialized, because if not,
     * this breaks the application when zf2's config cache is enabled.
     *
     * @covers \FinalGene\DoctrineModule\Module::getConfig()
     * @uses \FinalGene\DoctrineModule\Module::loadConfig()
     */
    public function testModuleConfigIsSerializable()
    {
        $module = new Module();

        if (!$module instanceof ConfigProviderInterface) {
            $this->markTestSkipped('Module does not provide config');
        }

        $this->assertEquals($module->getConfig(), unserialize(serialize($module->getConfig())));
    }

    /**
     * @covers \FinalGene\DoctrineModule\Module::getModuleDependencies()
     */
    public function testModuleDependencies()
    {
        $module = new Module();

        $this->assertInstanceOf(DependencyIndicatorInterface::class, $module);

        $dependencies = $module->getModuleDependencies();

        $this->assertInternalType('array', $dependencies);

        $this->assertContains('DoctrineModule', $dependencies);
        $this->assertContains('DoctrineORMModule', $dependencies);
    }

    /**
     * @covers \FinalGene\DoctrineModule\Module::loadConfig()
     * @expectedException \InvalidArgumentException
     */
    public function testLoadConfigThrowException()
    {
        $module = new Module();

        $this->assertInstanceOf(ConfigProviderInterface::class, $module);

        $config = $this->getMethod('loadConfig');
        $config->invokeArgs($module, ['not.existing.file']);
    }

    /**
     * @covers \FinalGene\DoctrineModule\Module::loadConfig()
     */
    public function testLoadConfigReturnConfigArray()
    {
        $module = new Module();

        $this->assertInstanceOf(ConfigProviderInterface::class, $module);

        $config = $this->getMethod('loadConfig');
        $config = $config->invokeArgs($module, ['tests/resources/Unit/ModuleTest/service.config.php']);

        $this->assertInternalType('array', $config);
    }

    /**
     * @covers \FinalGene\DoctrineModule\Module::init()
     */
    public function testInit()
    {
        $serviceListener = $this->getMockForAbstractClass(ServiceListenerInterface::class, [], '', true, true, true, ['addServiceManager']);
        $serviceListener
            ->expects($this->any())
            ->method('addServiceManager')
            ->withConsecutive(
                [
                    'EntityManager',
                    'entity_manager_config',
                    EntityManagerProviderInterface::class,
                    'getEntityManagerConfig',
                ],
                [
                    'RepositoryManager',
                    'repositories',
                    RepositoryProviderInterface::class,
                    'getRepositoryConfig',
                ]
            );

        $serviceLocator = $this->getMockForAbstractClass(ServiceLocatorInterface::class, [], '', true, true, true, ['get']);
        $serviceLocator
            ->expects($this->once())
            ->method('get')
            ->with('ServiceListener')
            ->willReturn($serviceListener);

        $event = $this->getMock(MvcEvent::class, ['getParam'], [], '', false);
        $event
            ->expects($this->once())
            ->method('getParam')
            ->with('ServiceManager')
            ->willReturn($serviceLocator);

        $manager = $this->getMockForAbstractClass(ModuleManagerInterface::class, [], '', true, true, true, ['getEvent']);
        $manager
            ->expects($this->once())
            ->method('getEvent')
            ->willReturn($event);
        /** @var ModuleManagerInterface $manager */

        $module = new Module();

        $this->assertInstanceOf(InitProviderInterface::class, $module);

        $module->init($manager);
    }

    /**
     * @param $name
     *
     * @return \ReflectionMethod
     */
    protected function getMethod($name)
    {
        $class = new \ReflectionClass(Module::class);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }
}
