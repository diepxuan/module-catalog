<?php
namespace Diepxuan\Catalog\Model\ResourceModel\Merchandiser;

use \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection {

    protected $_idFieldName = \Diepxuan\Catalog\Model\Merchandiser::MERCHANDISER_ID;

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct() {
        $this->_init('Diepxuan\Catalog\Model\Merchandiser', 'Diepxuan\Catalog\Model\ResourceModel\Merchandiser');
    }

}
