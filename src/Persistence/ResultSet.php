<?php

/*
 * This file is part of the IMTObjectMapper package.
 *
 * (c) Igor M. Timoshenko <igor.timoshenko@i.ua>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IMT\ObjectMapper\Persistence;

/**
 * @author Igor Timoshenko <i.timoshenko@i.ua>
 */
class ResultSet extends \IteratorIterator
{
    /**
     * @var string
     */
    private $class;

    /**
     * @var AbstractHydrator
     */
    private $hydrator;

    /**
     * @param \Traversable     $iterator
     * @param AbstractHydrator $hydrator
     * @param string           $class
     */
    public function __construct(\Traversable $iterator, AbstractHydrator $hydrator, $class)
    {
        parent::__construct($iterator);

        $this->hydrator = $hydrator;
        $this->class    = $class;
    }

    /**
     * {@inheritDoc}
     */
    public function current()
    {
        if ($data = parent::current()) {
            $data = $this->hydrator->hydrate($data, $this->class);
        }

        return $data;
    }
}
