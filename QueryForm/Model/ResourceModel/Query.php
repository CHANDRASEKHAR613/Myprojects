<?php
namespace Codilar\QueryForm\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Query extends AbstractDb
{
protected function _construct()
{
    $this->_init('query','id');
}
}
