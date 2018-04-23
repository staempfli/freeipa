<?php
declare(strict_types=1);

/**
 * @copyright Copyright © 2018 Stämpfli AG. All rights reserved.
 * @author Marcel Hauri <marcel.hauri@staempfli.com>
 */

namespace Staempfli\FreeIpa\Client;

use Staempfli\FreeIpa\Connection;

/**
 * Class User
 * @package Staempfli\FreeIpa\Client
 */
class User
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(
        Connection $connection
    ) {
        $this->connection = $connection;
    }

    public function find(string $method = '', array $params = [], array $arguments = [])
    {
        $defaultArguments = [
            'all' => true,
            'no_members' => false,
            'pkey_only' => false,
            'raw' => false,
            'whoami' => false,
        ];

        $mergedArguments = array_merge($defaultArguments, $arguments);

        $this->connection->buildRequest($method, $params, $mergedArguments);
        return Connection\Validator::json($this->connection->getContent());
    }

    public function findByFirstName(string $value)
    {
        return $this->find('user_find', [], ['givenname' => $value]);
    }

    public function findByLastName(string $value)
    {
        return $this->find('user_find', [], ['sn' => $value]);
    }

    public function findByFullName(string $value)
    {
        return $this->find('user_find', [], ['cn' => $value]);
    }

    public function findByGroup(string $value)
    {
        return $this->find('user_find', [], ['in_group' => $value]);
    }

    public function findByNotInGroup(string $value)
    {
        return $this->find('user_find', [], ['not_in_group' => $value]);
    }

    public function findByMail(string $value)
    {
        return $this->find('user_find', [], ['mail' => $value]);
    }

    public function findByUniqueName(string $value)
    {
        return $this->find('user_find', [], ['uid' => $value]);
    }

    public function findByUniqueNumber(string $value)
    {
        return $this->find('user_find', [], ['uidnumber' => $value]);
    }
}
