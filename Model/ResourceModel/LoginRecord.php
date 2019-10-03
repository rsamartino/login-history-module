<?php

namespace Rich\LoginHistory\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class LoginRecord extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('login_history','login_id');
    }
}
