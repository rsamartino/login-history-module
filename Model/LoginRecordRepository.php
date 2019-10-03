<?php

namespace Rich\LoginHistory\Model;

use Rich\LoginHistory\Api\Data\LoginRecordInterface;
use Rich\LoginHistory\Api\Data\LoginRecordInterfaceFactory;
use Rich\LoginHistory\Api\LoginRecordRepositoryInterface;
use Rich\LoginHistory\Model\ResourceModel\LoginRecord as LoginRecordResource;
use Rich\LoginHistory\Model\ResourceModel\LoginRecord\CollectionFactory;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;


class LoginRecordRepository implements LoginRecordRepositoryInterface
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    private $loginRecordFactory;

    private $resourceModel;

    private $searchResultsFactory;

    public function __construct(
        CollectionFactory $collectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        LoginRecordInterfaceFactory $loginRecordFactory,
        LoginRecordResource $resourceModel,
        SearchResultsInterfaceFactory $searchResultsFactory
    )
    {
        $this->collectionFactory = $collectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->loginRecordFactory = $loginRecordFactory;
        $this->resourceModel = $resourceModel;
        $this->searchResultsFactory = $searchResultsFactory;
    }

    /**
     * @param LoginRecordInterface $loginRecord
     * @return LoginRecordInterface
     * @throws CouldNotSaveException
     */
    public function save(LoginRecordInterface $loginRecord)
    {
        try {
            $this->resourceModel->save($loginRecord);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(
                __('The "%1" checkout agreement couldn\'t be saved.', $loginRecord->getId())
            );
        }
        return $loginRecord;
    }

    public function getById($id)
    {
        /** @var LoginRecord $loginRecord */
        $loginRecord = $this->loginRecordFactory->create();
        $this->resourceModel->load($loginRecord, $id);
        if (!$loginRecord->getId()) {
            throw new NoSuchEntityException(__('Unable to find login record with ID "%1"', $id));
        }
        return $loginRecord;
    }

    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var \Rich\LoginHistory\Model\ResourceModel\LoginRecord\Collection $collection */
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var \Magento\Framework\Api\SearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * @param LoginRecordInterface $record
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(LoginRecordInterface $record)
    {
        try {
            $this->resourceModel->delete($record);
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(
                __('The "%1" login record couldn\'t be removed.', $record->getId())
            );
        }
        return true;
    }

    /**
     * @param $id
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($id)
    {
        $model = $this->getById($id);
        $this->delete($model);
        return true;
    }

}
