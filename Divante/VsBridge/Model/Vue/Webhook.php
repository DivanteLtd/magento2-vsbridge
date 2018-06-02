<?php
/**
 * @package   Divante\VsBridge
 * @author    Mateusz Bukowski <mbukowski@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
 */

namespace Divante\VsBridge\Model\Vue;

use Divante\VsBridge\Logger\WebhookLoggerFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\HTTP\Adapter\Curl;
use Magento\Framework\Serialize\Serializer\Json;
use Monolog\Logger;

/**
 * Class Indexer
 */
abstract class Webhook
{

    /**
     * @var string
     */
    private static $isEnabledConfigPath = 'vs_bridge/general/is_active';
    /**
     * @var Curl
     */
    protected $curlAdapter;
    /**
     * @var WebhookLoggerFactory
     */
    private $indexerLoggerFactory;
    /**
     * @var Json
     */
    protected $jsonSerializer;
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;
    /**
     * @var string
     */
    public static $secretKeyConfigPath = 'vs_bridge/general/secret_key';

    /**
     * AbstractClass constructor.
     *
     * @param Curl                 $curlAdapter
     * @param WebhookLoggerFactory $indexerLoggerFactory
     * @param Json                 $jsonSerializer
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Curl $curlAdapter,
        WebhookLoggerFactory $indexerLoggerFactory,
        Json $jsonSerializer,
        ScopeConfigInterface $scopeConfig
    )
    {
        $this->curlAdapter          = $curlAdapter;
        $this->indexerLoggerFactory = $indexerLoggerFactory;
        $this->jsonSerializer       = $jsonSerializer;
        $this->scopeConfig          = $scopeConfig;
    }

    /**
     * @param string[] $identifiers
     *
     * @return string
     */
    private function hashChecksum(array $identifiers): string
    {
        $secretKey          = $this->scopeConfig->getValue(self::$secretKeyConfigPath);
        $encodedIdentifiers = $this->encodeIdentifiers($identifiers);

        return md5($encodedIdentifiers . $secretKey);
    }

    /**
     * @return bool
     */
    protected function getIsEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(self::$isEnabledConfigPath);
    }

    /**
     * @return Logger
     *
     * @throws \Exception
     */
    protected function getLogger(): Logger
    {
        return $this->indexerLoggerFactory->create();
    }

    /**
     * @param string[] $identifiers
     *
     * @return string
     */
    protected function encodeIdentifiers(array $identifiers): string
    {
        return urlencode(implode(',', $identifiers));
    }

    /**
     * @param string   $configPath
     * @param string[] $identifiers
     *
     * @return string|false
     */
    protected function getEndpoint(string $configPath, array $identifiers = [])
    {
        $endpoint = $this->scopeConfig->getValue($configPath);

        if (empty($endpoint) || !$this->getIsEnabled()) {
            return false;
        }

        return empty($identifiers) ? $endpoint : $endpoint . '?checksum=' . $this->hashChecksum($identifiers);
    }

    /**
     * @param string $url
     * @param array  $data
     * @param array  $headers
     *
     * @return void
     */
    protected function sendRequest(string $url, array $data, array $headers = ['Content-Type: application/json'])
    {
        if ($this->getIsEnabled()) {
            $jsonBody = $this->jsonSerializer->serialize($data);

            $request  = $this->curlAdapter->write('POST', $url, '1.1', $headers, $jsonBody);
            $response = $this->curlAdapter->read();

            $this->curlAdapter->close();

            try {
                $logger = $this->getLogger();

                $logger->debug($request);
                $logger->debug($response);
            } catch (\Exception $e) {
                // DO NOTHING
            }
        }
    }
}
