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
class DateTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DateType
     */
    private $type;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $this->type = new DateType();
    }

    public function testToDatabaseNull()
    {
        $this->assertNull($this->type->toDatabase(null));
    }

    public function testToDatabaseNumeric()
    {
        $time = time();

        $this->assertEquals(new \MongoDate($time), $this->type->toDatabase($time));
    }

    public function testToDatabaseString()
    {
        $time = (string) time();

        $this->assertEquals(new \MongoDate($time), $this->type->toDatabase($time));
    }

    public function testToDatabaseInvalid()
    {
        $this->setExpectedException('IMT\ObjectMapper\Exception\InvalidArgumentException');

        $this->type->toDatabase(false);
    }

    public function testToDatabase()
    {
        $value = new \DateTime();

        $this->assertEquals((new \MongoDate($value->getTimestamp())), $this->type->toDatabase($value));
    }

    public function testToPhpNull()
    {
        $this->assertNull($this->type->toPhp(null));
    }

    public function testToPhpNumeric()
    {
        $time = time();

        $this->assertEquals((new \DateTime())->setTimestamp($time), $this->type->toPhp($time));
    }

    public function testToPhpString()
    {
        $time = (string) time();

        $this->assertEquals((new \DateTime())->setTimestamp($time), $this->type->toPhp($time));
    }

    public function testToPhpInvalid()
    {
        $this->setExpectedException('IMT\ObjectMapper\Exception\InvalidArgumentException');

        $this->type->toPhp(false);
    }

    public function testToPhp()
    {
        $now   = new \DateTime();
        $value = new \MongoDate($now->getTimestamp());

        $this->assertEquals($now, $this->type->toPhp($value));
    }
}
