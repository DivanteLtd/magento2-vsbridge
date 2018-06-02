<?php
/**
 * @package   Divante\VsBridge
 * @author    Mateusz Bukowski <mbukowski@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
 */

namespace Divante\VsBridge\Logger;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

/**
 * Class IndexerLoggerFactory
 */
class WebhookLoggerFactory
{

    /**
     * @var string
     */
    private static $path = BP . '/var/log/vs-bridge.log';

    /**
     * @param string $channelName
     *
     * @return Logger
     *
     * @throws \Exception
     */
    public function create(string $channelName = 'vs-bridge'): Logger
    {
        $logger = new Logger($channelName);
        $logger->pushHandler(new StreamHandler(self::$path));

        return $logger;
    }
}
