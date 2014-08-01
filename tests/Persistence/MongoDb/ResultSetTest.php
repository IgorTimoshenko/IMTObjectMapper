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
class ResultSetTest extends \PHPUnit_Framework_TestCase
{
    public function testCurrent()
    {
        $resultSet = new ResultSet(new \ArrayIterator(array(array('_id' => 100500))));
        $resultSet->rewind();

        $this->assertEquals(array('id' => 100500), $resultSet->current());
    }
}
