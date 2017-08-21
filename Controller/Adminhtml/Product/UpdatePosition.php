<?php

namespace Diepxuan\Catalog\Controller\Adminhtml\Product;

use \Diepxuan\Catalog\Model\Merchandiser;
use \Magento\Backend\App\Action\Context;
use \Magento\Framework\Controller\Result\JsonFactory;

class UpdatePosition extends \Magento\Backend\App\Action
{
    protected $resultJsonFactory;
    protected $modelMerchandiser;
    public function __construct(
        Context      $context,
        JsonFactory  $resultJsonFactory,
        Merchandiser $modelMerchandiser
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->modelMerchandiser = $modelMerchandiser;
        return parent::__construct($context);
    }

    public function execute()
    {
        $posts           = array();
        $posts['cateID'] = $this->getRequest()->getPost('cateID', 0);
        $posts['items']  = $this->getRequest()->getPost('items', []);
        return $this->resultJsonFactory->create()->setData($this->modelMerchandiser->updatePositionItems($posts));
    }

    protected function _isAllowed()
    {
        return true;
    }

}
