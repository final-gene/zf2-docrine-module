<?php
/**
 * Module file
 *
 * @copyright Copyright (c) 2016, final gene <info@final-gene.de>
 * @author    Frank Giesecke <frank.giesecke@final-gene.de>
 */

namespace FinalGene\DoctrineModule\Doctrine\DBAL\Platforms;

use Doctrine\DBAL\Platforms\MySqlPlatform as BasePlatform;

/**
 * Class MySqlPlatform
 *
 * @package FinalGene\DoctrineModule\Doctrine\DBAL\Platforms
 */
class MySqlPlatform extends BasePlatform
{
    public function supportsForeignKeyConstraints()
    {
        return false;
    }
}
