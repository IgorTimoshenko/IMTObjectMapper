<?php

/*
 * This file is part of the IMTObjectMapper package.
 *
 * (c) Igor M. Timoshenko <igor.timoshenko@i.ua>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

function includeIfExists($file)
{
    if (file_exists($file)) {
        return include $file;
    }
}

if ((!$loader = includeIfExists(__DIR__ . '/../vendor/autoload.php'))
    && (!$loader = includeIfExists(__DIR__ . '/../../../autoload.php'))) {
    echo 'You must set up the project dependencies, run the following commands:' . PHP_EOL.
        'wget http://getcomposer.org/composer.phar' . PHP_EOL.
        'php composer.phar install' . PHP_EOL;

    exit(1);
}

$loader->addPsr4('IMT\\ObjectMapper\\', __DIR__);
