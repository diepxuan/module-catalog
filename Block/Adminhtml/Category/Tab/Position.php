<?php

/**
 * Copyright © 2017 Dxvn, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Diepxuan\Catalog\Block\Adminhtml\Category\Tab;

class Position extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var string
     */
    protected $_template = 'Diepxuan_Catalog::widget/grid/position.phtml';

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;

    /**
     * @var \Magento\Catalog\Helper\Image
     */
    protected $_imageHelper;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $_urlBuilder;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data            $backendHelper
     * @param \Magento\Catalog\Model\ProductFactory   $productFactory
     * @param \Magento\Framework\Registry             $coreRegistry
     * @param \Magento\Catalog\Helper\Image           $imageHelper
     * @param \Magento\Framework\UrlInterface         $urlBuilder
     * @param array                                   $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data            $backendHelper,
        \Magento\Catalog\Model\ProductFactory   $productFactory,
        \Magento\Framework\Registry             $coreRegistry,
        \Magento\Catalog\Helper\Image           $imageHelper,
        \Magento\Framework\UrlInterface         $urlBuilder,
        array                                   $data = []
    ) {
        $this->_productFactory = $productFactory;
        $this->_coreRegistry   = $coreRegistry;
        $this->_imageHelper    = $imageHelper;
        $this->_urlBuilder     = $urlBuilder;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return array|null
     */
    public function getCategory()
    {
        return $this->_coreRegistry->registry('category');
    }

    /**
     * @return Grid
     */
    protected function _prepareCollection()
    {
        if ($this->getCategory()->getId()) {
            $this->setDefaultFilter(['in_category' => 1]);
        }
        $collection = $this->_productFactory->create()->getCollection()
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('thumbnail')
            ->joinField(
                'position',
                'catalog_category_product',
                'position',
                'product_id=entity_id',
                'category_id=' . (int)$this->getRequest()->getParam('id', 0),
                'left'
            )
            ->setOrder('position', 'ASC');
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        if ($storeId > 0) {
            $collection->addStoreFilter($storeId);
        }
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * @return Extended
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            [
                'index'            => 'entity_id',
                'column_css_class' => 'admin__position-id col-id',
            ]
        );
        $this->addColumn(
            'thumbnail',
            [
                'index'            => 'thumbnail',
                'column_css_class' => 'admin__position-thumbnail',
            ]
        );
        $this->addColumn(
            'name',
            [
                'index'            => 'name',
                'column_css_class' => 'admin__position-name',
            ]
        );
        $this->addColumn(
            'position',
            [
                'index'            => 'cat_index_position',
                'column_css_class' => 'admin__position-position',
            ]
        );

        return $this;
    }

    /**
     * Prepare Thumbnail
     *
     * @param \Magento\Catalog\Model\Product\Interceptor $dataSource
     * @return string
     */
    public function prepareThumbnail($product)
    {
        $imageHelper = $this->_imageHelper->init($product, 'product_listing_thumbnail');

        $_src  = $imageHelper->getUrl();
        $_alt  = $this->getAlt($product) ?: $imageHelper->getLabel();
        $_link = $this->_urlBuilder->getUrl(
            'catalog/product/edit',
            [
                'id'    => $product->getEntityId(),
                'store' => (int) $this->getRequest()->getParam('store', 0),
            ]
        );

        return sprintf(
            '<img src="%s" alt="%s" />',
            $_src,
            $_alt
        );
    }

    /**
     * @param array $row
     *
     * @return null|string
     */
    protected function getAlt($row)
    {
        $altField = $this->getData('config/altField') ?: 'name';
        return isset($row[$altField]) ? $row[$altField] : null;
    }
}
