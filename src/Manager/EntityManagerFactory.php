<?php
/**
 * File for EntityManagerFactory class
 *
 * @copyright Copyright (c) 2016, final gene <info@final-gene.de>
 * @author    Frank Giesecke <frank.giesecke@final-gene.de>
 */

namespace FinalGene\DoctrineModule\Manager;

use FinalGene\DoctrineModule\Service\DoctrineEntityManagerFactory;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory for EntityManager class
 *
 * @package FinalGene\DoctrineModule\Manager
 */
class EntityManagerFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return EntityManager
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $repositoryManager = new EntityManager();

        $repositoryManager->setServiceLocator($serviceLocator);
        $repositoryManager->addAbstractFactory(new DoctrineEntityManagerFactory());

        return $repositoryManager;
    }

}
