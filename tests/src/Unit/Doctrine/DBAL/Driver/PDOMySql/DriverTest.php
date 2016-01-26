<?php
/**
 * File for DriverTest class
 *
 * @copyright Copyright (c) 2016, final gene <info@final-gene.de>
 * @author    Frank Giesecke <frank.giesecke@final-gene.de>
 */

namespace FinalGene\DoctrineModuleTest\Unit\Doctrine\DBAL\Driver\PDOMySql;

use FinalGene\DoctrineModule\Doctrine\DBAL\Driver\PDOMySql\Driver;
use FinalGene\DoctrineModule\Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Platforms\MySqlPlatform as BasePlatform;

/**
 * Class DriverTest
 *
 * @package FinalGene\DoctrineModuleTest\Unit\Doctrine\DBAL\Driver\PDOMySql
 */
class DriverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers FinalGene\DoctrineModule\Doctrine\DBAL\Driver\PDOMySql\Driver::getDatabasePlatform
     * @uses FinalGene\DoctrineModule\Doctrine\DBAL\Platforms\MySqlPlatform
     */
    public function testGetDatabasePlatform()
    {
        $driver = new Driver();
        $platform = $driver->getDatabasePlatform();

        $this->assertInstanceOf(MySqlPlatform::class, $platform);
        $this->assertInstanceOf(BasePlatform::class, $platform);
    }
}
