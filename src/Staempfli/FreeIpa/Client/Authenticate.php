<?php
declare(strict_types=1);

/**
 * @copyright Copyright © 2018 Stämpfli AG. All rights reserved.
 * @author Marcel Hauri <marcel.hauri@staempfli.com>
 */

namespace Staempfli\FreeIpa\Client;

use Staempfli\FreeIpa\Connection;
use Staempfli\FreeIpa\Exception\InvalidLoginException;

/**
 * Class Authenticate
 * @package Staempfli\FreeIpa\Client
 */
class Authenticate
{
    /**
     * @var Connection
     */
    private $connection;
    /**
     * @var string
     */
    private $username = '';
    /**
     * @var string
     */
    private $password = '';

    public function __construct(
        Connection $connection,
        string $username,
        string $password
    ) {
        $this->connection = $connection;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @throws InvalidLoginException
     */
    public function login()
    {
        $params = [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Accept' => '*/*',
            ],
            'body' => http_build_query([
                'user' => $this->username,
                'password' => $this->password
            ]),
        ];
        try {
            $this->connection->post('/ipa/session/login_password', $params);
        } catch (\Exception $e) {
            throw new InvalidLoginException('Authentication failed');
        }
    }
}
