<?php
/**
 * @package   Divante\VsBridge
 * @author    Mateusz Bukowski <mbukowski@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
 */

namespace Divante\VsBridge\Api\Vue\Webhook\Catalog;

/**
 * Interface ProductWebhookInterface
 */
interface ProductWebhookInterface
{

    /**
     * @param array $productSkuArray
     *
     * @return void
     */
    public function sendSkuDataAfterSave(array $productSkuArray);

    /**
     * @param array $productSkuArray
     *
     * @return void
     */
    public function sendSkuDataAfterDelete(array $productSkuArray);
}
