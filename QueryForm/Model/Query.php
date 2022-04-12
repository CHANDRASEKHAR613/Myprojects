<?php
namespace Codilar\QueryForm\Model;

use Magento\Framework\Model\AbstractModel;

class Query extends AbstractModel{

    protected function _construct()
    {
        $this->_init(\Codilar\QueryForm\Model\ResourceModel\Query::class);
    }
}
