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
class IntegerTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var IntegerType
     */
    private $type;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $this->type = new IntegerType();
    }

    public function testToDatabaseNull()
    {
        $this->assertNull($this->type->toDatabase(null));
    }

    public function testToDatabase()
    {
        $value = '1267174266165415';

        $this->assertEquals(new \MongoInt64($value), $this->type->toDatabase($value));
    }

    public function testToPhpNull()
    {
        $this->assertNull($this->type->toPhp(null));
    }

    public function testToPhp()
    {
        $value = new \MongoInt64('1267174266165415');

        $this->assertSame((int) (string) $value, $this->type->toPhp($value));
    }
}
