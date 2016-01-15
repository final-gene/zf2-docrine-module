<?php
/**
 * Module file
 *
 * @copyright Copyright (c) 2016, final gene <info@final-gene.de>
 * @author    Frank Giesecke <frank.giesecke@final-gene.de>
 */

namespace FinalGene\DoctrineModule\Doctrine\DBAL\Driver\PDOMySql;

use Doctrine\DBAL\Driver\PDOMySql\Driver as BaseDriver;
use FinalGene\DoctrineModule\Doctrine\DBAL\Platforms\MySqlPlatform;

/**
 * Class Driver
 *
 * @package FinalGene\DoctrineModule\Doctrine\DBAL\Driver\PDOMySql
 */
class Driver extends BaseDriver
{
    public function getDatabasePlatform()
    {
        return new MySqlPlatform();
    }
}
