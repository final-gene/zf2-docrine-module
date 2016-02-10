<?php
/**
 * File for ObjectExistsFactory class
 *
 * @copyright Copyright (c) 2016, final gene <info@final-gene.de>
 * @author    Frank Giesecke <frank.giesecke@final-gene.de>
 */

namespace FinalGene\DoctrineModule\Validator;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\MutableCreationOptionsTrait;

/**
 * Class ObjectExistsFactory
 *
 * @package FinalGene\DoctrineModule\Validator
 */
class ObjectExistsFactory implements FactoryInterface, MutableCreationOptionsInterface
{
    use MutableCreationOptionsTrait;

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $objectExistsValidator = new ObjectExists(
            $this->creationOptions,
            $serviceLocator->getServiceLocator()->get('RepositoryManager')
        );

        return $objectExistsValidator;
    }
}
