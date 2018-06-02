<?php
/**
 * @package   Divante\VsBridge
 * @author    Mateusz Bukowski <mbukowski@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
 */

namespace Divante\VsBridge\Observer\Product;

use Divante\VsBridge\Api\Vue\Webhook\Catalog\ProductWebhookInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class DeleteAfter
 */
class DeleteAfter implements ObserverInterface
{

    /**
     * @var ProductWebhookInterface
     */
    private $productIndexer;

    /**
     * SaveAfter constructor.
     *
     * @param ProductWebhookInterface $productIndexer
     */
    public function __construct(ProductWebhookInterface $productIndexer)
    {
        $this->productIndexer = $productIndexer;
    }

    /**
     * @param Observer $observer
     *
     * @return void
     */
    public function execute(Observer $observer)
    {
        /** @var Product $product */
        $product = $observer->getEvent()->getData('product');

        $this->productIndexer->sendSkuDataAfterDelete([$product->getSku()]);
    }
}
