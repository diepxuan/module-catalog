<?php
namespace Diepxuan\Catalog\Controller\Adminhtml\Product;

use \Magento\Backend\App\Action\Context;
use \Magento\Framework\Controller\Result\JsonFactory;
use \Diepxuan\Catalog\Model\Merchandiser;

class Reindexall extends \Magento\Backend\App\Action {
    protected $resultJsonFactory;
    protected $modelMerchandiser;
    public function __construct( Context $context, JsonFactory $resultJsonFactory, Merchandiser $modelMerchandiser ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->modelMerchandiser = $modelMerchandiser;
        return parent::__construct($context);
    }

    public function execute() {
        $cateID = $this->getRequest()->getPost('cateID', 0);
        return $this->resultJsonFactory->create()->setData($this->modelMerchandiser->autoSetPositionForProducts($cateID));
    }
    protected function _isAllowed()
    {
        return true;
    }

}
