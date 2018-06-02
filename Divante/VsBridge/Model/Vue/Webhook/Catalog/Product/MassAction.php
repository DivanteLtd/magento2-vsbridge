<?php
/**
 * @package   Divante\VsBridge
 * @author    Mateusz Bukowski <mbukowski@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
 */

namespace Divante\VsBridge\Model\Vue\Webhook\Catalog\Product;

use Divante\VsBridge\Api\Vue\Webhook\Catalog\ProductWebhookInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;

/**
 * Class MassAction
 */
class MassAction
{

    /**
     * @var ProductWebhookInterface
     */
    private $productIndexer;
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * Save constructor.
     *
     * @param ProductWebhookInterface    $productIndexer
     * @param ProductRepositoryInterface $productRepository
     * @param SearchCriteriaBuilder      $searchCriteriaBuilder
     */
    public function __construct(
        ProductWebhookInterface $productIndexer,
        ProductRepositoryInterface $productRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    )
    {
        $this->productRepository     = $productRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->productIndexer        = $productIndexer;
    }

    /**
     * @param array $productIds
     *
     * @return void
     */
    public function update(array $productIds)
    {
        if (!empty($productIds)) {
            $searchCriteria = $this->searchCriteriaBuilder->addFilter('entity_id', $productIds, 'in')->create();
            $products       = $this->productRepository->getList($searchCriteria);

            if ($products->getTotalCount() > 0) {
                $productSkuArray = [];

                foreach ($products->getItems() as $product) {
                    $productSkuArray[] = $product->getSku();
                }

                $this->productIndexer->sendSkuDataAfterSave($productSkuArray);
            }
        }
    }
}
