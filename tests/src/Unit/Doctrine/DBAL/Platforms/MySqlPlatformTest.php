<?php
/**
 * File for MySqlPlatformTest class
 *
 * @copyright Copyright (c) 2016, final gene <info@final-gene.de>
 * @author    Frank Giesecke <frank.giesecke@final-gene.de>
 */

namespace FinalGene\DoctrineModuleTest\Doctrine\DBAL\Platforms;

use FinalGene\DoctrineModule\Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Platforms\MySqlPlatform as BasePlatform;

/**
 * Class DriverTest
 *
 * @package FinalGene\DoctrineModuleTest\Unit\Doctrine\DBAL\Driver\PDOMySql
 */
class MySqlPlatformTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers FinalGene\DoctrineModule\Doctrine\DBAL\Platforms\MySqlPlatform::supportsForeignKeyConstraints
     */
    public function testSupportsForeignKeyConstraints()
    {
        $platform = new MySqlPlatform();

        $this->assertInstanceOf(BasePlatform::class, $platform);
        $this->assertFalse($platform->supportsForeignKeyConstraints());
    }
}
