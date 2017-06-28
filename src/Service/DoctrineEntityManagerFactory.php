<?php
/**
 * File for DoctrineEntityManagerFactory class
 *
 * @copyright Copyright (c) 2016, final gene <info@final-gene.de>
 * @author    Frank Giesecke <frank.giesecke@final-gene.de>
 */

namespace FinalGene\DoctrineModule\Service;

use Doctrine\ORM\EntityManagerInterface;
use FinalGene\DoctrineModule\Exception\ConfigurationException;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory to proxy the repository creation to the doctrine entitymanagers's repositoryfactory
 *
 * @package FinalGene\RepositoryManagerModule
 */
class DoctrineEntityManagerFactory implements AbstractFactoryInterface
{
    /**
     * Get/create a doctrine entity manager based on the entity
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param string $entityName
     *
     * @return Object|null
     * @throws ConfigurationException
     */
    protected function getManager(ServiceLocatorInterface $serviceLocator, $entityName)
    {
        /** @var AbstractPluginManager $serviceLocator */
        $serviceManager = $serviceLocator->getServiceLocator();

        // Get configuration
        $config = $serviceManager->get('config');
        if (!isset($config['doctrine']['driver'])) {
            throw new ConfigurationException('Doctrine driver configuration missing');
        }

        // Get entity namespace
        $reflection = new \ReflectionClass($entityName);
        $entityNamespace = $reflection->getNamespaceName();
        $entityPath = dirname($reflection->getFileName());

        $entityManager = null;

        // Loop through configured drivers for entity managers
        foreach ($config['doctrine']['driver'] as $entityManagerName => $configuration) {
            // Skip managers without configuration
            if (empty($configuration['drivers'])) {
                continue;
            }

            // Check namespaces
            foreach ($configuration['drivers'] as $driverKey => $driverName) {
                // Driver key matches namespace
                if ($driverKey === $entityNamespace) {
                    $entityManager = $serviceManager->get('doctrine.entitymanager.' . $entityManagerName);
                    break;
                }

                // Driver key matches namespace
                if (empty($config['doctrine']['driver'][$driverName])
                ||  !is_array($config['doctrine']['driver'][$driverName]['paths'])
                ) {
                    continue;
                }

                foreach ($config['doctrine']['driver'][$driverName]['paths'] as $path) {
                    $path = realpath($path);
                    if ($path === $entityPath) {
                        $entityManager = $serviceManager->get('doctrine.entitymanager.' . $entityManagerName);
                        break;
                    }
                }

                if (null !== $entityManager) {
                    break;
                }
            }

            if (null !== $entityManager) {
                break;
            }
        }

        return $entityManager;
    }

    /**
     * Determine if the EntityManager knows the path/namespace for this entity
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param                         $name
     * @param                         $requestedName
     *
     * @return bool
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        return $this->getManager($serviceLocator, $requestedName) instanceof EntityManagerInterface;
    }

    /**
     * Get the EntityManager
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param                         $name
     * @param                         $requestedName
     *
     * @return mixed
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        return $this->getManager($serviceLocator, $requestedName);
    }
}
