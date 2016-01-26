<?php
/**
 * File for RepositoryProviderInterface interface
 *
 * @copyright Copyright (c) 2016, final gene <info@final-gene.de>
 * @author    Frank Giesecke <frank.giesecke@final-gene.de>
 */

namespace FinalGene\DoctrineModule\ModuleManager\Feature;

/**
 * Interface to configure the EntityManager in the module class
 *
 * @package FinalGene\DoctrineModule
 */
interface EntityManagerProviderInterface
{
    /**
     * Returns configuration for EntityManager
     *
     * @return array servicemanager configuration
     */
    public function getEntityManagerConfig();
}
