<?php
/**
 * Module file
 *
 * @copyright Copyright (c) 2016, final gene <info@final-gene.de>
 * @author    Frank Giesecke <frank.giesecke@final-gene.de>
 */

namespace FinalGene\DoctrineModule;

use FinalGene\DoctrineModule\ModuleManager\Feature\EntityManagerProviderInterface;
use FinalGene\DoctrineModule\ModuleManager\Feature\RepositoryProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\DependencyIndicatorInterface;
use Zend\ModuleManager\Feature\InitProviderInterface;
use Zend\ModuleManager\Listener\ServiceListenerInterface;
use Zend\ModuleManager\ModuleManagerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Module
 *
 * @package FinalGene\DoctrineModule
 */
class Module implements ConfigProviderInterface, DependencyIndicatorInterface, InitProviderInterface
{
    /**
     * @inheritdoc
     */
    public function getConfig()
    {
        $config = [];
        $configFiles = [
            'config/service.config.php',
            'config/validators.config.php',
        ];

        foreach ($configFiles as $configFile) {
            $config = array_merge_recursive($config, $this->loadConfig($configFile));
        }

        return $config;
    }

    /**
     * Load config
     *
     * @param string $name Name of the configuration
     *
     * @throws \InvalidArgumentException if config could not be loaded
     *
     * @return array
     */
    protected function loadConfig($name)
    {
        $filename = __DIR__ . '/../' . $name;
        if (!is_readable($filename)) {
            throw new \InvalidArgumentException('Could not load config ' . $name);
        }

        /** @noinspection PhpIncludeInspection */
        return require $filename;
    }

    /**
     * Expected to return an array of modules on which the current one depends on
     *
     * @return array
     */
    public function getModuleDependencies()
    {
        return [
            'DoctrineModule',
            'DoctrineORMModule',
        ];
    }

    /**
     * @inheritdoc
     */
    public function init(ModuleManagerInterface $manager)
    {
        /** @var ServiceLocatorInterface $serviceManager */
        $serviceManager = $manager->getEvent()->getParam('ServiceManager');

        /** @var ServiceListenerInterface $serviceListener */
        $serviceListener = $serviceManager->get('ServiceListener');

        // Register entity manager
        $serviceListener->addServiceManager(
            'EntityManager',
            'entity_manager_config',
            EntityManagerProviderInterface::class,
            'getEntityManagerConfig'
        );

        // Register repository manager
        $serviceListener->addServiceManager(
            'RepositoryManager',
            'repositories',
            RepositoryProviderInterface::class,
            'getRepositoryConfig'
        );
    }
}
