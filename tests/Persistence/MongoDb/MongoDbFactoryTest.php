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
class MongoDbFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MongoDbFactory
     */
    private $mongoDbFactory;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $this->mongoDbFactory = new MongoDbFactory();
    }

    public function testCreateInvalidConfig()
    {
        $this->setExpectedException('MongoConnectionException');

        $this->mongoDbFactory->create('host', 'port', 'username', 'password', 'db');
    }

    public function testCreate()
    {
        $this->assertInstanceOf(
            'MongoDb',
            $this->mongoDbFactory->create(
                '127.0.0.1',
                27017,
                '',
                '',
                'test',
                array(
                    'timeout' => 2000,
                )
            )
        );
    }
}
