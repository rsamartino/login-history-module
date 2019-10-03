<?php

namespace Rich\LoginHistory\Model\ResourceModel\LoginRecord;

use \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(\Rich\LoginHistory\Model\LoginRecord::class,\Rich\LoginHistory\Model\ResourceModel\LoginRecord::class);
    }
}
