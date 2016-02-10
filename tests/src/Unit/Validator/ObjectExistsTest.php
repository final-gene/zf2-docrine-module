<?php
/**
 * File for ObjectExistsTest class
 *
 * @copyright Copyright (c) 2016, final gene <info@final-gene.de>
 * @author    Frank Giesecke <frank.giesecke@final-gene.de>
 */

namespace FinalGene\DoctrineModuleTest\Unit\Validator;

use FinalGene\DoctrineModule\Manager\RepositoryManager;
use FinalGene\DoctrineModule\Validator\ObjectExists;
use FinalGene\DoctrineModuleTest\Resources\Entity\TestEntity;
use FinalGene\DoctrineModuleTest\Resources\Repository\TestRepository;

/**
 * Class ObjectExistsTest
 *
 * @package FinalGene\DoctrineModuleTest\Unit\Validator
 */
class ObjectExistsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers FinalGene\DoctrineModule\Validator\ObjectExists::__construct
     * @expectedException \Zend\Validator\Exception\InvalidArgumentException
     */
    public function testCreateValidatorWillThrowExceptionOnMissingEntityClass()
    {
        $validator = new ObjectExists([], $this->getRepositoryManager());
    }

    /**
     * @covers FinalGene\DoctrineModule\Validator\ObjectExists::__construct
     * @expectedException \Zend\Validator\Exception\InvalidArgumentException
     */
    public function testCreateValidatorWillThrowExceptionOnNonMissingFindMethod()
    {
        $validator = new ObjectExists(
            [
                'entity_class' => TestEntity::class,
                'find_method' => 'foo',
            ],
            $this->getRepositoryManager()
        );
    }

    /**
     * @covers FinalGene\DoctrineModule\Validator\ObjectExists::__construct
     */
    public function testCreateValidator()
    {
        $validator = new ObjectExists(
            [
                'entity_class' => TestEntity::class,
                'find_method' => 'findOneBy',
            ],
            $this->getRepositoryManager()
        );
        $this->assertInstanceOf(ObjectExists::class, $validator);
    }

    /**
     * @covers FinalGene\DoctrineModule\Validator\ObjectExists::isValid
     * @uses FinalGene\DoctrineModule\Validator\ObjectExists::__construct
     */
    public function testObjectDoesNotExist()
    {
        $options =             [
            'entity_class' => TestEntity::class,
            'find_method' => 'findAll',
        ];

        $validator = new ObjectExists($options, $this->getRepositoryManager());
        $this->assertFalse($validator->isValid(0));
    }

    /**
     * @covers FinalGene\DoctrineModule\Validator\ObjectExists::isValid
     * @uses FinalGene\DoctrineModule\Validator\ObjectExists::__construct
     */
    public function testObjectExists()
    {
        $options =             [
            'entity_class' => TestEntity::class,
            'find_method' => 'find',
        ];

        $validator = new ObjectExists($options, $this->getRepositoryManager());
        $this->assertTrue($validator->isValid(1));
    }

    /**
     * @return RepositoryManager
     */
    private function getRepositoryManager()
    {
        $repository = $this->getMock(TestRepository::class, ['find'], [], '', false);
        $repository
            ->expects($this->any())
            ->method('find')
            ->with(1)
            ->willReturn(new TestEntity());

        $repositoryManager = $this->getMock(RepositoryManager::class, ['get'], [], '', false);
        $repositoryManager
            ->expects($this->any())
            ->method('get')
            ->with(TestEntity::class)
            ->willReturn($repository);

        return $repositoryManager;
    }

}
