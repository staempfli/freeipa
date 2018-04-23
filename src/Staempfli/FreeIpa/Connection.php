<?php
declare(strict_types=1);

/**
 * @copyright Copyright © 2018 Stämpfli AG. All rights reserved.
 * @author Marcel Hauri <marcel.hauri@staempfli.com>
 */

namespace Staempfli\FreeIpa;

use GuzzleHttp\Psr7\Response;
use Staempfli\FreeIpa\Connection\Certificate;
use Staempfli\FreeIpa\Connection\Validator;

/**
 * Class Connection
 * @package Staempfli\FreeIpa
 */
class Connection
{
    /**
     * @var \GuzzleHttp\Client
     */
    private $client;
    /**
     * @var string
     */
    private $url = '';
    /**
     * @var string
     */
    private $certificate = '';
    /**
     * @var Response
     */
    private $response;

    public function __construct(
        string $url = '',
        string $certificate = ''
    ) {
        if (empty($certificate)) {
            $certificate = Certificate::get($url);
        }

        $this->url = Validator::url($url);
        $this->certificate = Validator::certificate($certificate);

        $this->client = new \GuzzleHttp\Client([
            'cookies' => true,
            'base_uri' => $this->url,
            'verify' => $this->certificate,
            'allow_self_signed' => true
        ]);
    }

    public function get(string $path, array $params = [])
    {
        $this->response = $this->client->get($path, $params);
        return $this;
    }

    public function post(string $path, array $params = [])
    {
        $this->response = $this->client->post($path, $params);
        return $this;
    }

    public function getResponse() : Response
    {
        return $this->response;
    }

    public function getContent() : string
    {
        return (string) $this->getResponse()->getBody();
    }

    public function buildRequest(string $method, array $params = [], array $arguments = [])
    {
        $data = $this->getJsonRequest($method, $params, $arguments);
        $parameters = [
            'headers' => [
                'referer' => sprintf('%s/ipa/ui/index.html', $this->url),
                'Content-Type' => 'application/json',
                'Accept' => 'applicaton/json',
                'Content-Length' => strlen($data),
            ],
            'body' => $data,
        ];
        $this->post('/ipa/session/json', $parameters);
    }

    private function getJsonRequest(string $method, array $params, array $arguments) : string
    {
        if (empty($arguments)) {
            $arguments = new \stdClass();
        }
        $data = [
            'method' => $method,
            'params' => [$params, $arguments],
            'id' => time(),

        ];
        return json_encode($data);
    }
}
