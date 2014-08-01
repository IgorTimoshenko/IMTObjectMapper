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

use IMT\ObjectMapper\Persistence\AbstractHydrator;

/**
 * @author Igor Timoshenko <i.timoshenko@i.ua>
 */
class Hydrator extends AbstractHydrator
{
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'mongo_db';
    }

    /**
     * {@inheritDoc}
     */
    public function getType($type)
    {
        return Type\Type::getType($type);
    }
}
