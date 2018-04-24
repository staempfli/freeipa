<?php
/**
 * @copyright Copyright © 2018 Stämpfli AG. All rights reserved.
 * @author Marcel Hauri <marcel.hauri@staempfli.com>
 */

namespace Staempfli\Eyebase;

use PHPUnit\Framework\TestCase;
use Staempfli\FreeIpa\Client;
use Staempfli\FreeIpa\Exception\InvalidLoginException;

/**
 * Class ClientTest
 * @package Staempfli\Eyebase
 */
class ClientTest extends TestCase
{
    /**
     * @var Client
     */
    private static $client;


    public static function setUpBeforeClass()
    {
        self::$client = new Client('http://127.0.0.1:8082');
    }

    public function testClientWithCaFile()
    {
        $client = new Client('http://127.0.0.1:8082', __DIR__ . '/server/ca.crt');
        $this->assertInstanceOf(Client::class, $client);
    }

    public function testInvalidConnection()
    {
        $this->expectException(\Exception::class);
        new Client('http://127.0.0.1:8083');
    }

    public function testInvalidUrl()
    {
        $this->expectException(\LogicException::class);
        new Client('test');
    }

    public function testInvalidAuthentication()
    {
        $this->expectException(InvalidLoginException::class);
        self::$client->authenticate('test', 'invalid');
    }

    public function testValidAuthentication()
    {
        $this->assertNull(self::$client->authenticate('test', 'test'));
    }

    public function testPing()
    {
        $result = self::$client->ping();
        $this->assertSame('IPA server version 4.1.4. API version 2.114', $result);
    }

    public function testIfUserIsInstanceOfUser()
    {
        $user = self::$client->user();
        $this->assertInstanceOf(Client\User::class, $user);
    }

    public function testUserFindByFirstName()
    {
        $result = self::$client->user()->findByFirstName('Marcel');
        $this->assertSame($result[0]['cn'][0], 'Marcel Hauri');
    }

    public function testUserFindByLastName()
    {
        $result = self::$client->user()->findByLastName('Hauri');
        $this->assertSame($result[0]['cn'][0], 'Marcel Hauri');
    }

    public function testUserFindByFullName()
    {
        $result = self::$client->user()->findByFullName('Marcel Hauri');
        $this->assertSame($result[0]['cn'][0], 'Marcel Hauri');
    }

    public function testUserFindByGroup()
    {
        $result = self::$client->user()->findByGroup('admin');
        $this->assertSame($result[0]['cn'][0], 'Marcel Hauri');
    }

    public function testUserFindByNotInGroup()
    {
        $result = self::$client->user()->findByNotInGroup('web');
        $this->assertSame($result[0]['cn'][0], 'Marcel Hauri');
    }

    public function testUserFindByMail()
    {
        $result = self::$client->user()->findByMail('marcel.hauri@staempfli.com');
        $this->assertSame($result[0]['cn'][0], 'Marcel Hauri');
    }

    public function testUserFindByUniqueName()
    {
        $result = self::$client->user()->findByUniqueName('marcel');
        $this->assertSame($result[0]['cn'][0], 'Marcel Hauri');
    }

    public function testUserFindByUniqueNumber()
    {
        $result = self::$client->user()->findByUniqueNumber('100000');
        $this->assertSame($result[0]['cn'][0], 'Marcel Hauri');
    }
}
