<?php
declare(strict_types=1);

/**
 * @copyright Copyright © 2018 Stämpfli AG. All rights reserved.
 * @author Marcel Hauri <marcel.hauri@staempfli.com>
 */
namespace Staempfli\FreeIpa\Connection;

use GuzzleHttp\Exception\ClientException;

/**
 * Class Certificate
 * @package Staempfli\FreeIpa\Connection
 */
class Certificate
{
    const CERTIFICATE_PATH = 'ipa/config/ca.crt';

    public static function get(string $url) : string
    {
        $client = new \GuzzleHttp\Client([
            'cookies' => true,
            'verify' => false
        ]);
        $urlWithPath = sprintf('%s/%s', Validator::url($url), self::CERTIFICATE_PATH);
        try {
            $result = $client->get($urlWithPath);
            if (200 === $result->getStatusCode()) {
                $content = $result->getBody()->getContents();
                $tmpFile = tempnam(sys_get_temp_dir(), 'ca.crt');
                file_put_contents($tmpFile, $content);

                return $tmpFile;
            }
        } catch (ClientException $e) {
        }
        return '';
    }
}
