<?php
/**
 * File for ObjectExists class
 *
 * @copyright Copyright (c) 2016, final gene <info@final-gene.de>
 * @author    Frank Giesecke <frank.giesecke@final-gene.de>
 */

namespace FinalGene\DoctrineModule\Validator;

use Doctrine\Common\Persistence\ObjectRepository;
use Zend\Stdlib\ArrayUtils;
use Zend\Validator\AbstractValidator;
use Zend\Validator\Exception;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class that validates if objects exist in a given repository with a given list of matched fields
 *
 * @license MIT
 * @link    http://www.doctrine-project.org/
 * @since   0.4.0
 * @author  Marco Pivetta <ocramius@gmail.com>
 */
class ObjectExists extends AbstractValidator
{
    /**
     * Error constants
     */
    const ERROR_NO_OBJECT_FOUND = 'noObjectFound';

    /**
     * @var array Message templates
     */
    protected $messageTemplates = array(
        self::ERROR_NO_OBJECT_FOUND => "No object matching '%value%' was found",
    );

    /**
     * @var string
     */
    protected $entityClass;

    /**
     * @var ObjectRepository
     */
    protected $objectRepository;

    /**
     * @var string
     */
    protected $findMethodName;

    /**
     * Constructor
     *
     * @param array $options Options
     * @param ServiceLocatorInterface $repositoryManager Repository manager object
     *
     * @throws \Zend\Validator\Exception\InvalidArgumentException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function __construct(array $options, ServiceLocatorInterface $repositoryManager)
    {
        $options = array_merge(
            [
                'find_method' => 'find',
            ],
            $options
        );

        if (!isset($options['entity_class'])) {
            throw new Exception\InvalidArgumentException('Option `entity_class` is required');
        }
        $this->entityClass = $options['entity_class'];
        $this->objectRepository = $repositoryManager->get($this->entityClass);

        if (!method_exists($this->objectRepository, $options['find_method'])) {
            throw new Exception\InvalidArgumentException(
                sprintf('Method `%s` not found in repository `%s`', $this->findMethodName, get_class($this->objectRepository))
            );
        }
        $this->findMethodName = $options['find_method'];

        parent::__construct($options);
    }

    /**
     * {@inheritDoc}
     */
    public function isValid($value)
    {
        $match = $this->objectRepository->{$this->findMethodName}($value);
        if ($match instanceof $this->entityClass) {
            return true;
        }

        $this->error(self::ERROR_NO_OBJECT_FOUND, $value);
        return false;
    }
}
