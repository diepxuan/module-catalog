<?php
/**
 * Copyright © 2016 Evolve.
 */

namespace Diepxuan\Catalog\Block\Adminhtml\Category;

class AssignProducts extends \Magento\Backend\Block\Template
{

    // Block template
    protected $_template = 'catalog/category/edit/assign_products.phtml';
    // Magento\Catalog\Block\Adminhtml\Category\Tab\Product
    protected $blockGrid;
    // Magento\Framework\Registry
    protected $registry;
    // Magento\Framework\Json\EncoderInterface
    protected $jsonEncoder;
    // Image
    protected $helperImage;
    // Pagination
    protected $_paginationPageSizes = array(
        20  => 20,
        40  => 40,
        60  => 60,
        80  => 80,
        100 => 100,
        150 => 150,
        200 => 200,
    );

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
        $this->registry    = $registry;
        $this->jsonEncoder = $jsonEncoder;
        $this->helperImage = $helperImage;
        parent::__construct($context, $data);
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
            // ->setPageSize($pageSize)
            // ->setCurPage($page)
                ->setOrder('position', 'ASC');
        }
        return $collections;
    }

    /**
     * Get category id
     */
    public function getCategoryId()
    {
        $cate = $this->getCategory();
        if (!is_null($cate)) {return $cate->getId();} else {return 0;}
    }

    /**
     * Get category
     */
    public function getCategory()
    {
        return $this->registry->registry('category');
    }

    /**
     * Get image product
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
