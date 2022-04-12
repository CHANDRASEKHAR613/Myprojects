<?php
namespace Codilar\QueryForm\Model\ResourceModel\QueryCollection;

use Codilar\QueryForm\Model\Query;
use Codilar\QueryForm\Model\ResourceModel\Query as ResourceModelQuery;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(Query::class, ResourceModelQuery::class);
    }
}