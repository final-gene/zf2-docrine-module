<?php
/**
 * File for EntityManagerTest class
 *
 * @copyright Copyright (c) 2016, final gene <info@final-gene.de>
 * @author    Frank Giesecke <frank.giesecke@final-gene.de>
 */

namespace FinalGene\DoctrineModuleTest\Unit\Manager;

use FinalGene\DoctrineModule\Manager\EntityManager;

/**
 * Class EntityManagerTest
 *
 * @package FinalGene\DoctrineModuleTest\Unit\Manager
 */
class EntityManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers FinalGene\DoctrineModule\Manager\EntityManager::validatePlugin
     */
    public function testValidatePlugin()
    {
        $plugin = new EntityManager();
        $plugin->validatePlugin(null);
        $this->assertNull(null);
    }
}
