<?php
/**
 * Copyright © 2016 Dxvn.
 */

namespace Diepxuan\Catalog\Block\Adminhtml\Category;

class PositionProducts extends \Magento\Catalog\Block\Adminhtml\Category\AssignProducts
{

    protected $_template = 'catalog/category/edit/position_products.phtml';

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
}
