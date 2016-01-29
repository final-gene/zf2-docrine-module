<?php
/**
 * File for RepositoryManagerTest class
 *
 * @copyright Copyright (c) 2016, final gene <info@final-gene.de>
 * @author    Frank Giesecke <frank.giesecke@final-gene.de>
 */

namespace FinalGene\DoctrineModuleTest\Unit\Manager;

use FinalGene\DoctrineModule\Manager\RepositoryManager;

/**
 * Class RepositoryManagerTest
 *
 * @package FinalGene\DoctrineModuleTest\Unit\Manager
 */
class RepositoryManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers FinalGene\DoctrineModule\Manager\RepositoryManager::validatePlugin
     */
    public function testValidatePlugin()
    {
        $plugin = new RepositoryManager();
        $plugin->validatePlugin(null);
        $this->assertNull(null);
    }
}
