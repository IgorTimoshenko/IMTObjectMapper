<?php

/*
 * This file is part of the IMTObjectMapper package.
 *
 * (c) Igor M. Timoshenko <igor.timoshenko@i.ua>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IMT\ObjectMapper\Persistence\MongoDb;

/**
 * @author Igor Timoshenko <i.timoshenko@i.ua>
 */
class HydratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Hydrator
     */
    private $hydrator;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->hydrator = new Hydrator(include __DIR__ . '/../../metadata.smth.php');
    }

    public function testGetName()
    {
        $this->assertEquals('mongo_db', $this->hydrator->getName());
    }

    public function testGetType()
    {
        $this->assertInstanceOf(
            'IMT\ObjectMapper\Persistence\TypeInterface',
            $this->hydrator->getType('smth')
        );
    }
}
