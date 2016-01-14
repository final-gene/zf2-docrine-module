<?php
/**
 * This file is part of the doctrine-module.php project.
 *
 * @copyright Copyright (c) 2016, final gene <info@final-gene.de>
 * @author    Frank Giesecke <frank.giesecke@final-gene.de>
 */

namespace FinalGene\DoctrineModuleTest\Unit;

use FinalGene\DoctrineModule\Module;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

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

        $config = $this->getMethod('loadConfig');
        $config->invokeArgs($module, ['not.existing.file']);
    }

    /**
     * @covers \FinalGene\DoctrineModule\Module::loadConfig()
     */
    public function testLoadConfigReturnConfigArray()
    {
        $module = new Module();

        $config = $this->getMethod('loadConfig');
        $config = $config->invokeArgs($module, ['tests/resources/Unit/ModuleTest/service.config.php']);

        $this->assertInternalType('array', $config);
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
