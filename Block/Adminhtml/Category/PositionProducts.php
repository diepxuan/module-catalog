<?php

/**
 * Copyright © 2016 Dxvn.
 */
namespace Diepxuan\Catalog\Block\Adminhtml\Category;

class PositionProducts extends \Magento\Catalog\Block\Adminhtml\Category\AssignProducts
{

    protected $_template = 'catalog/category/edit/position_products.phtml';

    /**
     * @var \Magento\Catalog\Helper\Image
     */
    protected $helperImage;

    /**
     * AssignProducts constructor.
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context  $context,
        \Magento\Framework\Registry              $registry,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Catalog\Helper\Image            $helperImage,
        array                                    $data = []
    ) {
        $this->helperImage = $helperImage;
        parent::__construct($context, $registry, $jsonEncoder, $data);
    }

    /**
     * Retrieve instance of grid block
     *
     * @return \Magento\Framework\View\Element\BlockInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getBlockGrid()
    {
        if (null === $this->blockGrid) {
            $this->blockGrid = $this->getLayout()->createBlock(
                'Diepxuan\Catalog\Block\Adminhtml\Category\Tab\Position',
                'category.product.position'
            );
        }
        return $this->blockGrid;
    }

    protected function getCollection()
    {
        return $this->getCategory()->getProductCollection();
    }

    /**
     * Get collections product by category
     */
    public function getCollectionProducts()
    {
        $collections = false;
        //get values of current page
        $page = $this->getRequest()->getParam('p') ? $this->getRequest()->getParam('p') : 1;
        //get values of current limit
        $pageSize = $this->getRequest()->getParam('limit') ? $this->getRequest()->getParam('limit') : 20;
        if (!is_null($this->getCategory())) {
            $collections = $this->getCollection()
                ->addAttributeToSelect(array('name', 'small_image', 'thumbnail', 'image', 'entity_id'))
                ->addAttributeToFilter('visibility', array('in' => array(
                    'both'       => \Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH,
                    'in_catalog' => \Magento\Catalog\Model\Product\Visibility::VISIBILITY_IN_CATALOG,
                    'in_search'  => \Magento\Catalog\Model\Product\Visibility::VISIBILITY_IN_SEARCH,
                )))
                ->setOrder('position', 'ASC');
        }
        return $collections;
    }

    /**
     * @return interger
     */
    public function getCategoryId()
    {
        return $this->getCategory()->getId() ?: 0;
    }

    /**
     * @param  [type] $product
     * @return string
     */
    public function getImage($product)
    {
        return $this->helperImage->init($product, 'product_page_image_small')
            ->constrainOnly(true)
            ->keepAspectRatio(true)
            ->keepFrame(false)
            ->setImageFile($product->getImage())->getUrl();
    }
}
