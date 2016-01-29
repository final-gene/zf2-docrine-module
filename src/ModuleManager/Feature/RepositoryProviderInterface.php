<?php
/**
 * File for RepositoryProviderInterface interface
 *
 * @copyright Copyright (c) 2015, final gene <info@final-gene.de>
 * @author    Frank Giesecke <frank.giesecke@final-gene.de>
 */

namespace FinalGene\DoctrineModule\ModuleManager\Feature;

/**
 * Interface to configure the RepositoryManager in the module class
 *
 * @package FinalGene\DoctrineModule
 */
interface RepositoryProviderInterface
{
    /**
     * Returns configuration for RepositoryManager
     *
     * @return array servicemanager configuration
     */
    public function getRepositoryConfig();
}
