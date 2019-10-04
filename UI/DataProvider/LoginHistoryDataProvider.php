<?php

namespace Rich\LoginHistory\UI\DataProvider;

use Magento\Framework\Api\FilterBuilder;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Api\Search\ReportingInterface;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\RequestInterface;

class LoginHistoryDataProvider extends \Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider
{
    /**
     * @var CustomerSession
     */
    private $customerSession;

    /**
     * LoginHistoryDataProvider constructor.
     * @param CustomerSession $customerSession
     * @param $name
     * @param $primaryFieldName
     * @param $requestFieldName
     * @param ReportingInterface $reporting
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param RequestInterface $request
     * @param FilterBuilder $filterBuilder
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        CustomerSession $customerSession,
        $name,
        $primaryFieldName,
        $requestFieldName,
        ReportingInterface $reporting,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        RequestInterface $request,
        FilterBuilder $filterBuilder,
        array $meta = [],
        array $data = []
    )
    {
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $reporting,
            $searchCriteriaBuilder,
            $request,
            $filterBuilder,
            $meta,
            $data
        );

        $this->customerSession = $customerSession;
        $this->addCustomerFilter();
    }

    /**
     * filter records by current customer
     */
    private function addCustomerFilter()
    {
        $customerFilter = $this->filterBuilder
            ->setField('customer_id')
            ->setValue($this->customerSession->getCustomerId())
            ->setConditionType('eq')
            ->create();

        parent::addFilter($customerFilter);
    }

}
