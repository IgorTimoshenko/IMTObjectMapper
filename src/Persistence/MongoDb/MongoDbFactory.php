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
class MongoDbFactory
{
    /**
     * @var array
     */
    private $defaultOptions = array(
        'connectTimeoutMS' => 10000,
    );

    /**
     * @param  string   $host
     * @param  integer  $port
     * @param  string   $username
     * @param  string   $password
     * @param  string   $db
     * @param  array    $options
     * @return \MongoDB
     */
    public function create($host, $port, $username, $password, $db, array $options = array())
    {
        $server = 'mongodb://';
        $server .= empty($username) && empty($password) ? '' : "$username:$password@";
        $server .= "$host:$port";

        $options = array_merge($this->defaultOptions, $options);

        $mongoClient = new \MongoClient($server, $options);

        return $mongoClient->selectDB($db);
    }
}
