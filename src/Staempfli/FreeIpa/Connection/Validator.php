<?php
declare(strict_types=1);

/**
 * @copyright Copyright © 2018 Stämpfli AG. All rights reserved.
 * @author Marcel Hauri <marcel.hauri@staempfli.com>
 */

namespace Staempfli\FreeIpa\Connection;

use Staempfli\FreeIpa\Exception\InvalidJsonException;
use Staempfli\FreeIpa\Exception\InvalidJsonRequestException;
use Staempfli\FreeIpa\Exception\InvalidJsonResultException;
use Staempfli\FreeIpa\Exception\InvalidOptionException;
use Staempfli\FreeIpa\Exception\InvalidVersionException;

/**
 * Class Validator
 * @package Staempfli\FreeIpa\Connection
 */
class Validator
{
    public static function url(string $url) : string
    {
        if (false === filter_var($url, FILTER_VALIDATE_URL)) {
            throw new \LogicException(sprintf('"%s" is not a valid url', $url));
        }
        return rtrim(filter_var($url, FILTER_VALIDATE_URL), '/');
    }

    public static function certificate(string $certificate) : string
    {
        if (empty($certificate)) {
            throw new \LogicException('No certification file set');
        }

        if (false === realpath($certificate)) {
            throw new \LogicException(sprintf('%s is not a valid file', $certificate));
        }

        if (false === is_readable(realpath($certificate))) {
            throw new \LogicException(sprintf('Can not read file: %s', $certificate));
        }
        return realpath($certificate) ?? '';
    }

    /**
     * @param string $json
     * @return array
     * @throws InvalidJsonRequestException
     * @throws InvalidJsonResultException
     * @throws InvalidOptionException
     * @throws InvalidVersionException
     */
    public static function json(string $json) : array
    {
        $data = json_decode($json, true);
        if (isset($data['error'])) {
            switch ($data['error']['name']) {
                case 'OptionError':
                    throw new InvalidOptionException($data['error']['message']);
                    break;
                case 'JSONError':
                    throw new InvalidJsonRequestException($data['error']['message']);
                    break;
                case 'VersionError':
                    throw new InvalidVersionException($data['error']['message']);
                    break;
                default:
                    throw new \LogicException($data['error']['message']);
            }
        }

        if (!isset($data['result'])) {
            throw new InvalidJsonResultException('result not set in json data');
        }

        return $data['result'];
    }
}
