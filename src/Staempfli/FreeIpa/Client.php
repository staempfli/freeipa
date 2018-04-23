<?php
declare(strict_types=1);

/**
 * @copyright Copyright © 2018 Stämpfli AG. All rights reserved.
 * @author Marcel Hauri <marcel.hauri@staempfli.com>
 */

namespace Staempfli\FreeIpa;

use Staempfli\FreeIpa\Client\Authenticate;
use Staempfli\FreeIpa\Client\User;
use Staempfli\FreeIpa\Connection\Validator;
use Staempfli\FreeIpa\Exception\InvalidJsonSummaryException;

/**
 * Class FreeIpa
 * @package Staempfli\FreeIpa
 */
class Client
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(
        string $url = '',
        string $certificate = ''
    ) {
        $this->setConnection($url, $certificate);
    }

    private function setConnection(string $url, string $certificate) : Connection
    {
        if (!$this->connection) {
            $this->connection = new Connection($url, $certificate);
        }
        return $this->connection;
    }

    public function ping()
    {
        $this->connection->buildRequest('ping');
        $result = Validator::json($this->connection->getContent());
        if (isset($result['summary'])) {
            return $result['summary'];
        }
        throw new InvalidJsonSummaryException('summary not set in json data');
    }

    /**
     * @param string $username
     * @param string $password
     * @throws Exception\InvalidLoginException
     */
    public function authenticate(string $username, string $password)
    {
        $auth = new Authenticate($this->connection, $username, $password);
        $auth->login();
    }

    public function user()
    {
        $user = new User($this->connection);
        return $user;
    }
}
