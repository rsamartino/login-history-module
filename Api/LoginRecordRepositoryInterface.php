<?php
namespace Rich\LoginHistory\Api;

use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Rich\LoginHistory\Api\Data\LoginRecordInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface LoginRecordRepositoryInterface 
{
    /**
     * @param LoginRecordInterface $loginRecord
     * @return LoginRecordInterface
     */
    public function save(LoginRecordInterface $loginRecord);

    /**
     * @param int $id
     * @return LoginRecordInterface
     */
    public function getById($id);

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * @param LoginRecordInterface $loginRecord
     * @return bool true on success
     * @throws CouldNotDeleteException
     */
    public function delete(LoginRecordInterface $loginRecord);

    /**
     * @param int $id
     * @return bool true on success
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($id);
}
