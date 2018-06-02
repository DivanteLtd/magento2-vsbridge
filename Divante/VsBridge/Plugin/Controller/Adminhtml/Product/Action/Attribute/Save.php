<?php
/**
 * @package   Divante\VsBridge
 * @author    Mateusz Bukowski <mbukowski@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
 */

namespace Divante\VsBridge\Plugin\Controller\Adminhtml\Product\Action\Attribute;

use Divante\VsBridge\Model\Vue\Webhook\Catalog\Product\MassAction;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Catalog\Controller\Adminhtml\Product\Action\Attribute\Save as SaveController;
use Magento\Catalog\Helper\Product\Edit\Action\Attribute;

/**
 * Class Save
 */
class Save
{

    /**
     * @var Attribute
     */
    private $attributeHelper;
    /**
     * @var MassAction
     */
    private $massAction;

    /**
     * Save constructor.
     *
     * @param Attribute  $attributeHelper
     * @param MassAction $massAction
     *
     */
    public function __construct(Attribute $attributeHelper, MassAction $massAction)
    {
        $this->attributeHelper = $attributeHelper;
        $this->massAction      = $massAction;
    }

    /**
     * @param SaveController $saveController
     * @param Redirect       $redirect
     *
     * @return Redirect
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterExecute(SaveController $saveController, Redirect $redirect)
    {
        $productIds = $this->attributeHelper->getProductIds();

        $this->massAction->update($productIds);

        return $redirect;
    }
}
