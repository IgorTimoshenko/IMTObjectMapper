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
class ResultSet extends \IteratorIterator
{
    /**
     * {@inheritDoc}
     */
    public function current()
    {
        if ($data = parent::current()) {
            $data['id'] = (string) $data['_id'];
            unset($data['_id']);
        }

        return $data;
    }
}
