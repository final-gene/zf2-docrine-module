<?php
/**
 * File for RepositoryManagerFactory class
 *
 * @copyright Copyright (c) 2015, final gene <info@final-gene.de>
 * @author    Frank Giesecke <frank.giesecke@final-gene.de>
 */

namespace FinalGene\DoctrineModule\Manager;

use FinalGene\DoctrineModule\Service\DoctrineObjectRepositoryFactory;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory for RepositoryManager class
 *
 * @package FinalGene\DoctrineModule
 */
class RepositoryManagerFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return RepositoryManager
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $repositoryManager = new RepositoryManager();

        $repositoryManager->setServiceLocator($serviceLocator);
        $repositoryManager->addAbstractFactory(new DoctrineObjectRepositoryFactory());

        return $repositoryManager;
    }

}
