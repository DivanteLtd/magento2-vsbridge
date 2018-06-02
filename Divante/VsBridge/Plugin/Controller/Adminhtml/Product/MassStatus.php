<?php
/**
 * @package   Divante\VsBridge
 * @author    Mateusz Bukowski <mbukowski@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
 */

namespace Divante\VsBridge\Plugin\Controller\Adminhtml\Product;

use Divante\VsBridge\Model\Vue\Webhook\Catalog\Product\MassAction;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Catalog\Controller\Adminhtml\Product\MassStatus as MassStatusController;
use Magento\Catalog\Helper\Product\Edit\Action\Attribute;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;

/**
 * Class MassStatus
 */
class MassStatus
{

    /**
     * @var Attribute
     */
    private $attributeHelper;
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;
    /**
     * @var Filter
     */
    private $filter;
    /**
     * @var MassAction
     */
    private $massAction;

    /**
     * MassStatus constructor.
     *
     * @param Attribute         $attributeHelper
     * @param CollectionFactory $collectionFactory
     * @param Filter            $filter
     * @param MassAction        $massAction
     */
    public function __construct(
        Attribute $attributeHelper,
        CollectionFactory $collectionFactory,
        Filter $filter,
        MassAction $massAction
    )
    {
        $this->attributeHelper   = $attributeHelper;
        $this->collectionFactory = $collectionFactory;
        $this->filter            = $filter;
        $this->massAction        = $massAction;
    }

    /**
     * @param MassStatusController $massStatusController
     * @param Redirect             $redirect
     *
     * @return Redirect
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterExecute(MassStatusController $massStatusController, Redirect $redirect)
    {
        try {
            $productsCollection = $this->filter->getCollection($this->collectionFactory->create());

            $this->massAction->update($productsCollection->getAllIds());
        } catch (LocalizedException $e) {
            // DO NOTHING
        }

        return $redirect;
    }
}
