<?php
/**
 * File for RepositoryManager class
 *
 * @copyright Copyright (c) 2015, final gene <info@final-gene.de>
 * @author    Frank Giesecke <frank.giesecke@final-gene.de>
 */

namespace FinalGene\DoctrineModule\Manager;

use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\Exception;

/**
 * A service manager for repositories
 *
 * @package FinalGene\DoctrineModule
 */
class RepositoryManager extends AbstractPluginManager
{
    /**
     * We don't want this, as we request the repository with the name of the entity from the RepositoryManager.
     *
     * If we enable this, we would always return the Entity itself instead of a matching repository class.
     *
     * @var bool
     */
    protected $autoAddInvokableClass = false;

    /**
     * Validate the plugin
     *
     * Checks that the filter loaded is either a valid callback or an instance
     * of FilterInterface.
     *
     * @param  mixed $plugin
     *
     * @return void
     * @throws Exception\RuntimeException if invalid
     */
    public function validatePlugin($plugin)
    {
    }
}
