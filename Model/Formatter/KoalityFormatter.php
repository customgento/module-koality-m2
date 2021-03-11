<?php

declare(strict_types=1);

namespace Koality\MagentoPlugin\Model\Formatter;

use Koality\MagentoPlugin\Api\ResultInterface;

class KoalityFormatter
{
    /**
     * @var ResultInterface[]
     */
    private $results;

    /**
     * Add a new result.
     *
     * If the status of the result is "fail" the whole check will be marked as failed.
     *
     * @param ResultInterface $result
     */
    public function addResult(ResultInterface $result): void
    {
        $this->results[] = $result;
    }

    /**
     * Return an IETF conform result array with all sub results.
     *
     * @return array
     */
    public function getFormattedResults(): array
    {
        $formattedResult = [];
        $checks          = [];
        $status          = ResultInterface::STATUS_PASS;

        foreach ($this->results as $result) {
            $check = [
                'status' => $result->getStatus(),
                'output' => $result->getMessage()
            ];

            if (is_numeric($result->getLimit())) {
                $check['limit'] = $result->getLimit();
            }

            if (!is_null($result->getLimitType())) {
                $check['limitType'] = $result->getLimitType();
            }

            if (!is_null($result->getObservedValue())) {
                $check['observedValue'] = $result->getObservedValue();
            }

            if (!is_null($result->getObservedValueUnit())) {
                $check['observedUnit'] = $result->getObservedValueUnit();
            }

            if (!is_null($result->getObservedValuePrecision())) {
                $check['observedValuePrecision'] = $result->getObservedValuePrecision();
            }

            if (!is_null($result->getType())) {
                $check['metricType'] = $result->getType();
            }

            $attributes = $result->getAttributes();
            if (count($attributes) > 0) {
                $check['attributes'] = $attributes;
            }

            $checks[$result->getKey()] = $check;

            if ($result->getStatus() === ResultInterface::STATUS_FAIL) {
                $status = ResultInterface::STATUS_FAIL;
            }
        }

        $formattedResult['status'] = $status;
        $formattedResult['output'] = $this->getOutput($status);
        $formattedResult['checks'] = $checks;
        $formattedResult['info']   = $this->getInfoBlock();

        return $formattedResult;
    }

    /**
     * Get the output string depending on the given status.
     *
     * @param string $status
     *
     * @return string
     */
    private function getOutput(string $status): string
    {
        if ($status === ResultInterface::STATUS_PASS) {
            return 'All Magento health metrics passed.';
        }

        return 'Some Magento health metrics failed: ';
    }

    /**
     * Return the info block for the JSON output
     */
    private function getInfoBlock(): array
    {
        return [
            'creator'    => 'koality.io Magento Plugin',
            'version'    => '1.0.0',
            'plugin_url' => 'https://www.koality.io/plugins/magento'
        ];
    }
}
