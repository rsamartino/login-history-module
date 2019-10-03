<?php
namespace Rich\LoginHistory\Api;

use Rich\LoginHistory\Api\Data\LoginRecordInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface LoginRecordRepositoryInterface 
{
    public function save(LoginRecordInterface $loginRecord);

    public function getById($id);

    public function getList(SearchCriteriaInterface $searchCriteria);

    public function delete(LoginRecordInterface $loginRecord);

    public function deleteById($id);
}
