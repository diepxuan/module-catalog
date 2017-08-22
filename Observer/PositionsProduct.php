<?php

namespace Diepxuan\Catalog\Observer;

class PositionsProduct implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $_jsonHelper;

    public function __construct(\Magento\Framework\Json\Helper\Data $jsonHelper)
    {
        $this->_jsonHelper = $jsonHelper;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $positionProducts = $observer->getRequest()->getPostValue('position_products', '{}');
        $category         = $observer->getCategory();

        if ($category->getProductsReadonly()) {
            return;
        }

        $products = $this->_jsonHelper->jsonDecode($positionProducts);
        $category->setPostedProducts($products);
    }
}
