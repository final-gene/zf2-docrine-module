<?php
/**
 * File for DoctrineObjectRepositoryFactory class
 *
 * @copyright Copyright (c) 2015, final gene <info@final-gene.de>
 * @author    Frank Giesecke <frank.giesecke@final-gene.de>
 */

namespace FinalGene\DoctrineModule\Service;

use Doctrine\Common\Persistence\ObjectRepository;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory to proxy the repository creation to the doctrine entitymanagers's repositoryfactory
 *
 * @package FinalGene\DoctrineModule
 */
class DoctrineObjectRepositoryFactory implements AbstractFactoryInterface
{
    /**
     * Get/create a doctrine repository from the entitymanager
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param string $entityName
     *
     * @return Object|null
     */
    protected function getRepository(ServiceLocatorInterface $serviceLocator, $entityName)
    {
        /** @var AbstractPluginManager $serviceLocator */
        $serviceManager = $serviceLocator->getServiceLocator();

        /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
        $entityManager = $serviceManager->get('EntityManager')->get($entityName);
        return $entityManager->getRepository($entityName);
    }

    /**
     * Determine if the EntityManager knows the repository for this entity
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param                         $name
     * @param                         $requestedName
     *
     * @return bool
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        return $this->getRepository($serviceLocator, $requestedName) instanceof ObjectRepository;
    }

    /**
     * Get the entity from the EntityManager
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param                         $name
     * @param                         $requestedName
     *
     * @return mixed
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        return $this->getRepository($serviceLocator, $requestedName);
    }
}
