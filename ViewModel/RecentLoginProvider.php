<?php

namespace Rich\LoginHistory\ViewModel;

use Rich\LoginHistory\Api\LoginRecordRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Api\SortOrderBuilder;

class RecentLoginProvider implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    private $customerSession;
    private $loginRecordRepository;
    private $searchCriteriaBuilder;
    private $sortOrderBuilder;

    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        LoginRecordRepositoryInterface $loginRecordRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SortOrderBuilder $sortOrderBuilder
    )
    {
        $this->customerSession = $customerSession;
        $this->loginRecordRepository = $loginRecordRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->sortOrderBuilder = $sortOrderBuilder;
    }

    /**
     * @return \Rich\LoginHistory\Api\Data\LoginRecordInterface
     */
    public function getRecentLogin()
    {
        $this->searchCriteriaBuilder->addFilter(
            'customer_id',
            $this->customerSession->getCustomerId(),
            'eq'
        );

        $sortOrder = $this->sortOrderBuilder
            ->setField('login_date')
            ->setDirection(SortOrder::SORT_DESC)
            ->create();

        $this->searchCriteriaBuilder->addSortOrder($sortOrder);

        $searchCriteria = $this->searchCriteriaBuilder->create();

        $logins = $this->loginRecordRepository
            ->getList($searchCriteria)
            ->getItems();

        if (count($logins) > 1) {
            /** @var \Rich\LoginHistory\Api\Data\LoginRecordInterface $recentLogin */
            $recentLogin = array_slice($logins, 1, 1)[0]; //get second most recent login
            return $recentLogin;
        } else {
            return null;
        }
    }

}
