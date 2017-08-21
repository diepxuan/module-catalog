<?php

namespace Diepxuan\Catalog\Observer;

class CatalogProductPosition implements \Magento\Framework\Event\ObserverInterface
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
        $post     = $observer->getRequest()->getPostValue();
        $category = $observer->getCategory();

        if ($category->getProductsReadonly()) {
            return;
        }

        if (empty($post['merchandiser_category_products'])) {
            return;
        }

        if (is_string($post['merchandiser_category_products'])) {
            $products = $this->_jsonHelper->jsonDecode($post['merchandiser_category_products']);
            $category->setPostedProducts($products);
        }
    }
}
