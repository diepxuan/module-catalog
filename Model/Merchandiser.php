<?php
namespace Diepxuan\Catalog\Model;

use \Magento\Framework\Model\AbstractModel;

class Merchandiser extends AbstractModel {
    const MERCHANDISER_ID = 'entity_id'; // We define the id fieldname

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'merchandisers'; // parent value is 'core_abstract'

    /**
     * Name of the event object
     *
     * @var string
     */
    protected $_eventObject = 'merchandiser'; // parent value is 'object'

    /**
     * Name of object id field
     *
     * @var string
     */
    protected $_idFieldName = self::MERCHANDISER_ID; // parent value is 'id'

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct() {
        $this->_init('Diepxuan\Catalog\Model\ResourceModel\Merchandiser');
    }

    /**
    *   Set position for all product
    **/
    public function autoSetPositionForProducts($cateID = 0, $options = array()) {
        try {
            $collections = $this->getCollection()->addFieldToFilter('category_id', $cateID)->setOrder('position','ASC');
            if ( !is_null($collections) AND $collections->getSize() )
            {
                $connection = $collections->getConnection();
                $conditions = array();
                $keys = array();
                $position = 0;
                foreach ($collections as $item)
                {
                    $position++;
                    $case = $connection->quoteInto('?', $item->getEntityId());
                    array_push($keys, $item->getEntityId());
                    $result = $connection->quoteInto('?', $position);
                    $conditions[$case] = $result;
                }
                $value = $connection->getCaseSql('entity_id', $conditions, 'position');
                $where = array('entity_id IN (?)' => $keys);
                return $this->_doUpdate(array('position' => $value), $where);
            }
            else { return array('status'=> true, 'msg' => "Don't have item update"); }
        } catch(\Exception $e) {
            return array('status'=> false, 'msg' => $e->getMessage());
        }
    }

    /**
    *   Update postion for items
    **/
    public function updatePositionItems($posts) {
        if ( !empty($posts) AND is_array($posts) AND !empty($posts['cateID']) AND !empty($posts['items']) AND is_array($posts['items']) AND count($posts['items']) )
        {
            try {
                $datas = array();
                foreach ($posts['items'] as $item)
                {
                    if ( array_key_exists('id', $item) AND array_key_exists('position', $item) ) { $datas[$item['id']] = $item['position'];  }
                }
                if ( count($datas) )
                {
                    $collections = $this->getCollection()
                        ->addFieldToFilter('category_id', $posts['cateID'])
                        ->addFieldToFilter('product_id', array('in' => array_keys($datas)))
                        ->setOrder('position','ASC');
                    if ( !is_null($collections) AND $collections->getSize() )
                    {
                        $connection = $collections->getConnection();
                        $conditions = array();
                        $keys = array();
                        foreach ($collections as $item)
                        {
                            if ( array_key_exists($item->getProductId(), $datas) )
                            {
                                $case = $connection->quoteInto('?', $item->getEntityId());
                                array_push($keys, $item->getEntityId());
                                $result = $connection->quoteInto('?', $datas[$item->getProductId()]);
                                $conditions[$case] = $result;
                            }
                        }
                        $value = $connection->getCaseSql('entity_id', $conditions, 'position');
                        $where = array('entity_id IN (?)' => $keys);
                        return $this->_doUpdate(array('position' => $value), $where);
                    }
                    else { return array('status'=> true, 'msg' => "Don't have item update"); }
                }
                else { return array('status'=> false, 'msg' => "A little paramster are missing.", 'items' => $posts); }
            } catch(\Exception $e) {
                return array('status'=> false, 'msg' => $e->getMessage());
            }
        }
        else { return array('status'=> false, 'msg' => "A little paramster are missing.", 'items' => $posts); }
    }

    /**
    *   Do save
    **/
    private function _doUpdate( $values, $where ) {
        try {
            $collections = $this->getCollection();
            $connection = $collections->getConnection();
            $connection->beginTransaction();
            $connection->update($collections->getTable('catalog_category_product'), $values, $where);
            $connection->commit();
            return array('status'=> true);
        } catch(\Exception $e) {
            return array('status'=> false, 'msg' => $e->getMessage());
        }
    }
}