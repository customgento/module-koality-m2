<?php

declare(strict_types=1);

namespace Koality\MagentoPlugin\Model;

use Koality\MagentoPlugin\Api\ResultInterface;
use Koality\MagentoPlugin\Model\Formatter\Result;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Koality\MagentoPlugin\Model\Config;

class ActiveProductsCollector
{
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var ResultInterface
     */
    private $resultInterface;

    /**
     * @var Config
     */
    private $config;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        ResultInterface $resultInterface,
        Config $config
    ) {
        $this->productRepository     = $productRepository;
        $this->resultInterface       = $resultInterface;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->config                = $config;
    }

    public function getAllProducts(): ResultInterface
    {
        //TODO check this variable against original

        $activeProductCount = $this->getActiveProductsCount();
        $minOpenProjects    = $this->config->getActiveProducts() ?? 0;

        if ($activeProductCount < $minOpenProjects) {
            $cartResult = new Result(ResultInterface::STATUS_FAIL, ResultInterface::KEY_PRODUCTS_ACTIVE,
                'There are too few active products in your shop.');
        } else {
            $cartResult = new Result(ResultInterface::STATUS_PASS, ResultInterface::KEY_PRODUCTS_ACTIVE,
                'There are enough active products in your shop.');
        }

        $this->resultInterface->setLimit($minOpenProjects);
        $this->resultInterface->setObservedValue($activeProductCount);
        $this->resultInterface->setObservedValueUnit('products');
        $this->resultInterface->setObservedValuePrecision(0);
        $this->resultInterface->setLimitType(ResultInterface::LIMIT_TYPE_MIN);
        $this->resultInterface->setType(ResultInterface::TYPE_TIME_SERIES_NUMERIC);

        return $cartResult;
    }

    /**
     * Return the number of active products.
     *
     * @return int
     */
    private function getActiveProductsCount(): int
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('status', Status::STATUS_ENABLED)
            ->create();

        return count($this->productRepository->getList($searchCriteria)->getItems());
    }
}
