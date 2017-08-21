<?php

namespace Diepxuan\Catalog\Model\ResourceModel;

/**
 * Department post mysql resource
 */
class Merchandiser extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        // Table Name and Primary Key column
        $this->_init('catalog_category_product', 'entity_id');
    }

}
