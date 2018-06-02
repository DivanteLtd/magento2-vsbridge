<?php
/**
 * @package   Divante\VsBridge
 * @author    Mateusz Bukowski <mbukowski@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
 */

namespace Divante\VsBridge\Model\Vue\Webhook\Catalog;

use Divante\VsBridge\Api\Vue\Webhook\Catalog\ProductWebhookInterface;
use Divante\VsBridge\Model\Vue\Webhook;

/**
 * Class ProductWebhook
 */
class ProductWebhook extends Webhook implements ProductWebhookInterface
{

    /**
     * @var string
     */
    private static $saveEndpointConfPath = 'vs_bridge/general/product_edit_endpoint';
    /**
     * @var string
     */
    private static $deleteEndpointConfPath = 'vs_bridge/general/product_delete_endpoint';

    /**
     * @param array $productSkuArray
     *
     * @return void
     */
    public function sendSkuDataAfterSave(array $productSkuArray)
    {
        $saveEndpoint = $this->getEndpoint(self::$saveEndpointConfPath, $productSkuArray);

        if ($saveEndpoint) {
            $this->sendRequest($saveEndpoint, ['sku' => $productSkuArray]);
        }
    }

    /**
     * @param array $productSkuArray
     *
     * @return void
     */
    public function sendSkuDataAfterDelete(array $productSkuArray)
    {
        $deleteEndpoint = $this->getEndpoint(self::$deleteEndpointConfPath, $productSkuArray);

        if ($deleteEndpoint) {
            $this->sendRequest($deleteEndpoint, ['sku' => $productSkuArray]);
        }
    }
}
