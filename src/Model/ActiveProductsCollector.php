<?php

declare(strict_types=1);

namespace Koality\MagentoPlugin\Model;

use Koality\MagentoPlugin\Api\ResultInterface;
use Koality\MagentoPlugin\Model\Formatter\Result;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Koality\MagentoPlugin\Model\Config;
use Koality\MagentoPlugin\Api\CollectorInterface;

class ActiveProductsCollector implements CollectorInterface
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
     * @var Config
     */
    private $config;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        Config $config
    ) {
        $this->productRepository     = $productRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->config                = $config;
    }

    public function getResult(): ResultInterface
    {
        $activeProductCount       = $this->getActiveProductsCount();
        $minNumberOfActiveProduct = $this->config->getActiveProducts() ?? 0;

        if ($activeProductCount < $minNumberOfActiveProduct) {
            $result = new Result(
                ResultInterface::STATUS_FAIL,
                ResultInterface::KEY_PRODUCTS_ACTIVE,
                'There are too few active products in your shop.'
            );
        } else {
            $result = new Result(
                ResultInterface::STATUS_PASS,
                ResultInterface::KEY_PRODUCTS_ACTIVE,
                'There are enough active products in your shop.'
            );
        }

        $result->setLimit($minNumberOfActiveProduct);
        $result->setObservedValue($activeProductCount);
        $result->setObservedValueUnit('products');
        $result->setObservedValuePrecision(0);
        $result->setLimitType(ResultInterface::LIMIT_TYPE_MIN);
        $result->setType(ResultInterface::TYPE_TIME_SERIES_NUMERIC);

        return $result;
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
