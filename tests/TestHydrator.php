<?php

/*
 * This file is part of the IMTObjectMapper package.
 *
 * (c) Igor M. Timoshenko <igor.timoshenko@i.ua>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IMT\ObjectMapper;

use IMT\ObjectMapper\Persistence\AbstractHydrator;

/**
 * @author Igor Timoshenko <i.timoshenko@i.ua>
 */
class TestHydrator extends AbstractHydrator
{
    /**
     * @var string
     */
    private $name;

    /**
     * {@inheritDoc}
     */
    public function __construct(array $metadata, $name)
    {
        parent::__construct($metadata);

        $this->name = $name;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function getType($type)
    {
        return new TestType();
    }
}
