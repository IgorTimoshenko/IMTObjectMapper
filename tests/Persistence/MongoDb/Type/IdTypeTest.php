<?php

/*
 * This file is part of the IMTObjectMapper package.
 *
 * (c) Igor M. Timoshenko <igor.timoshenko@i.ua>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IMT\ObjectMapper\Persistence\MongoDb\Type;

/**
 * @author Igor Timoshenko <i.timoshenko@i.ua>
 */
class IdTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var IdType
     */
    private $type;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $this->type = new IdType();
    }

    public function testToDatabaseNull()
    {
        $this->assertNull($this->type->toDatabase(null));
    }

    public function testToDatabase()
    {
        $value = '5328269c4a09428c3f000000';

        $this->assertEquals(new \MongoId($value), $this->type->toDatabase($value));
    }

    public function testToPhpNull()
    {
        $this->assertNull($this->type->toPhp(null));
    }

    public function testToPhpMongoId()
    {
        $value = new \MongoId('5328269c4a09428c3f000000');

        $this->assertSame((string) $value, $this->type->toPhp($value));
    }
}
