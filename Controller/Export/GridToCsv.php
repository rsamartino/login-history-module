<?php

namespace Rich\LoginHistory\Controller\Export;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Ui\Model\Export\ConvertToCsv;
use Psr\Log\LoggerInterface;

/**
 * copy over Magento\Ui\Controller\Adminhtml\Export\GridToCsv for frontend use
 */
class GridToCsv extends \Magento\Framework\App\Action\Action
{
    /**
     * @var ConvertToCsv
     */
    protected $converter;

    /**
     * @var FileFactory
     */
    protected $fileFactory;

    /**
     * @var Filter
     */
    private $filter;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param Context $context
     * @param ConvertToCsv $converter
     * @param FileFactory $fileFactory
     * @param Filter|null $filter
     * @param LoggerInterface|null $logger
     */
    public function __construct(
        Context $context,
        ConvertToCsv $converter,
        FileFactory $fileFactory,
        Filter $filter = null,
        LoggerInterface $logger = null
    ) {
        parent::__construct($context);
        $this->converter = $converter;
        $this->fileFactory = $fileFactory;
        $this->filter = $filter ?: ObjectManager::getInstance()->get(Filter::class);
        $this->logger = $logger ?: ObjectManager::getInstance()->get(LoggerInterface::class);
    }

    /**
     * Export data provider to CSV
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return \Magento\Framework\App\ResponseInterface
     */
    public function execute()
    {
        return $this->fileFactory->create('export.csv', $this->converter->getCsvFile(), 'var');
    }
}
