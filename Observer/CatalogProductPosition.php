<?php
namespace Diepxuan\Catalog\Observer;

use \Magento\Framework\Event\ObserverInterface;

class CatalogProductPosition implements ObserverInterface
{
    /**
     * execute
     * @param  \Magento\Framework\Event\Observer $observer
     * @return null
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $post = $observer->getRequest()->getPostValue();
        $category = $observer->getCategory();
        if (!empty($post['merchandiser_category_products'])
            && is_string($post['merchandiser_category_products'])
            && !$category->getProductsReadonly()
        ) {
            $products = json_decode($post['merchandiser_category_products'], true);
            $category->setPostedProducts($products);
        }
    }
}
