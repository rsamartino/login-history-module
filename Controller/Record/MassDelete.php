<?php

namespace Rich\LoginHistory\Controller\Record;

use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultFactory;
use Rich\LoginHistory\Model\ResourceModel\LoginRecord\CollectionFactory;
use Rich\LoginHistory\Api\LoginRecordRepositoryInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Framework\App\CsrfAwareActionInterface;

class MassDelete extends \Magento\Framework\App\Action\Action implements HttpPostActionInterface, CsrfAwareActionInterface
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var LoginRecordRepositoryInterface
     */
    private $loginRecordRepository;

    /**
     * MassDelete constructor.
     * @param CollectionFactory $collectionFactory
     * @param Context $context
     * @param Filter $filter
     * @param LoginRecordRepositoryInterface $loginRecordRepository
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        Context $context,
        Filter $filter,
        LoginRecordRepositoryInterface $loginRecordRepository
    ) {
        parent::__construct($context);
        $this->collectionFactory = $collectionFactory;
        $this->filter = $filter;
        $this->loginRecordRepository = $loginRecordRepository;
    }

    /**
     * fix 2.3 feature/bug?? https://github.com/magento/magento2/issues/19712#issuecomment-446557313
     *
     * @inheritDoc
     */
    public function createCsrfValidationException(
        RequestInterface $request
    ): ?InvalidRequestException {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function validateForCsrf(RequestInterface $request): ?bool
    {
        return true;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $recordDeleted = 0;
        /** @var \Rich\LoginHistory\Model\LoginRecord $record */
        foreach ($collection as $record) {
            $this->loginRecordRepository->delete($record);
            $recordDeleted++;
        }

        if ($recordDeleted) {
            $this->messageManager->addSuccessMessage(
                __('A total of %1 record(s) have been deleted.', $recordDeleted)
            );
        }

        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('login_history');
    }
}
