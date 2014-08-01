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

use IMT\ObjectMapper\TestHydrator;

/**
 * @author Igor Timoshenko <i.timoshenko@i.ua>
 */
class ResultSetTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ResultSet
     */
    private $resultSet;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->resultSet = new ResultSet(
            new \ArrayIterator(array(array('id' => 100500))),
            new TestHydrator(include __DIR__ . '/../metadata.smth.php', 'smth'),
            'IMT\ObjectMapper\Fixture\Post'
        );
    }

    public function testConstruct()
    {
        $this->assertAttributeInstanceOf(
            'IMT\ObjectMapper\TestHydrator',
            'hydrator',
            $this->resultSet
        );
        $this->assertAttributeEquals(
            'IMT\ObjectMapper\Fixture\Post',
            'class',
            $this->resultSet
        );
    }

    public function testCurrent()
    {
        $this->resultSet->rewind();

        $this->assertInstanceOf(
            'IMT\ObjectMapper\Fixture\Post',
            $this->resultSet->current()
        );
    }
}
