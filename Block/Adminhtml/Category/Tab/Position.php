<?php
/**
 * Copyright © 2017 Dxvn, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Diepxuan\Catalog\Block\Adminhtml\Category\Tab;

// class Position extends \Magento\Catalog\Block\Adminhtml\Category\Tab\Product
class Position extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var string
     */
    protected $_template = 'Diepxuan_Catalog::widget/grid/position.phtml';

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\Framework\Registry $coreRegistry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data            $backendHelper,
        \Magento\Catalog\Model\ProductFactory   $productFactory,
        \Magento\Framework\Registry             $coreRegistry,
        array                                   $data = []
    ) {
        $this->_productFactory = $productFactory;
        $this->_coreRegistry   = $coreRegistry;
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
        $collection = $this->_productFactory->create()->getCollection()->addAttributeToSelect(
            'name'
        )->addAttributeToSelect(
            'sku'
        )->addAttributeToSelect(
            'price'
        )->joinField(
            'position',
            'catalog_category_product',
            'position',
            'product_id=entity_id',
            'category_id=' . (int) $this->getRequest()->getParam('id', 0),
            'right'
        );
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
                'header'           => __('ID'),
                'sortable'         => true,
                'index'            => 'entity_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id',
            ]
        );
        $this->addColumn('name', ['header' => __('Name'), 'index' => 'name']);
        $this->addColumn(
            'position',
            [
                'header'   => __('Position'),
                'type'     => 'number',
                'index'    => 'position',
                'editable' => !$this->getCategory()->getProductsReadonly(),
            ]
        );

        return $this;
    }
}
