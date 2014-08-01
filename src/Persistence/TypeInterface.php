<?php

/*
 * This file is part of the IMTObjectMapper package.
 *
 * (c) Igor M. Timoshenko <igor.timoshenko@i.ua>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IMT\ObjectMapper\Persistence;

/**
 * @author Igor Timoshenko <i.timoshenko@i.ua>
 */
interface TypeInterface
{
    /**
     * @param  mixed $value
     * @return mixed
     */
    public function toDatabase($value);

    /**
     * @param  mixed $value
     * @return mixed
     */
    public function toPhp($value);
}
