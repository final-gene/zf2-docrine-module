<?php
/**
 * Validators config file
 *
 * @copyright Copyright (c) 2016, final gene <info@final-gene.de>
 * @author    Frank Giesecke <frank.giesecke@final-gene.de>
 */

namespace FinalGene\DoctrineModule;

return [
    'validators' => [
        'factories' => [
            Validator\ObjectExists::class => Validator\ObjectExistsFactory::class,
        ],
        'shared' => [
            Validator\ObjectExists::class => false,
        ]
    ],
];
