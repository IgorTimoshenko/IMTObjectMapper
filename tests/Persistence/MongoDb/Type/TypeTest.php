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
class TypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Type
     */
    private $type;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $this->type = new Type();
    }

    public function testGetTypeItself()
    {
        $this->assertInstanceOf(get_class($this->type), Type::getType('smth'));
    }

    public function testGetType()
    {
        $this->assertInstanceOf('IMT\ObjectMapper\Persistence\TypeInterface', Type::getType('id'));
    }

    public function testGetTypeMemoized()
    {
        $type = Type::getType('id');

        $this->assertSame($type, Type::getType('id'));
    }

    public function testDatabase()
    {
        $this->assertSame(100500, $this->type->toDatabase(100500));
    }

    public function testPhp()
    {
        $this->assertSame(100500, $this->type->toPhp(100500));
    }
}
