<?php

/*
 * This file is part of the IMTObjectMapper package.
 *
 * (c) Igor M. Timoshenko <igor.timoshenko@i.ua>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IMT\ObjectMapper;

use IMT\ObjectMapper\Persistence\TypeInterface;

/**
 * @author Igor Timoshenko <i.timoshenko@i.ua>
 */
class TestType implements TypeInterface
{
    /**
     * {@inheritDoc}
     */
    public function toDatabase($value)
    {
        return $value;
    }

    /**
     * {@inheritDoc}
     */
    public function toPhp($value)
    {
        return $value;
    }
}
