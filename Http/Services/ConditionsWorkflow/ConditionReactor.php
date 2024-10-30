<?php

declare (strict_types = 1);

namespace WMIP2C\Http\Services\ConditionsWorkflow;

use WMIP2C\Common\Enums\WordpressOptions;
use WMIP2C\Database\Models\Operator;
use WMIP2C\Database\Repositories\ConditionWithCombinationRepository;
use WMIP2C\Http\Data\ConditionCombinationOperatorFieldAggregate;
use WMIP2C\Http\Services\CacheService;
use WMIP2C\Http\Services\ConditionsWorkflow\ConditionValueProviders\ConditionValueProvider;
use WMIP2C\Http\Services\ConditionsWorkflow\Operators\Assert;
use WMIP2C\Http\Services\Statistics\ConditionsStatisticsManager;

final class ConditionReactor
{
    private ConditionValueProvider $conditionValueProvider;
    private ConditionsStatisticsManager $conditionsStatisticsManager;
    private ConditionWithCombinationRepository $conditionWithCombinationRepository;

    private CacheService $cacheService;

    public function __construct(
        ConditionValueProvider $conditionValueProvider,
        ConditionsStatisticsManager $conditionsStatisticsManager,
        ConditionWithCombinationRepository $conditionWithCombinationRepository
    ) {
        $this->conditionValueProvider = $conditionValueProvider;
        $this->conditionsStatisticsManager = $conditionsStatisticsManager;
        $this->conditionWithCombinationRepository = $conditionWithCombinationRepository;
        $this->cacheService = new CacheService();
    }

    public function applyConditions(int $userId, array $conditionsIds): ConditionContentBag
    {
        $conditionContentBag = new ConditionContentBag();


        $cachedSettings = $this->cacheService->get("settings");
        if ($cachedSettings !== null) {
            $settings = json_decode($cachedSettings, true);
        } else {
            $settings = [];
        }

        $ip2cActive = $settings['ip2c'] ?? get_option(WordpressOptions::IP2C_ACTIVE, 0);

        if ($ip2cActive === 0) {
            return $conditionContentBag;
        }

        $ip2cToken = get_option(WordpressOptions::IP2C_TOKEN, null);

        if ($ip2cToken === null) {
            return $conditionContentBag;
        }

        foreach ($conditionsIds as $conditionId) {
            $condition = $this->cacheService->get(sprintf('condition.%d', $conditionId));
            if ($condition === null) {
                $cc = $this->conditionWithCombinationRepository->getActiveWhereIdIn([$conditionId]);
                if (count($cc) > 0) {
                    $conditionAggregates[] = $cc[0];
                    $this->cacheService->set(sprintf('condition.%d', $conditionId), serialize($cc[0]));
                }
            } else {
                $conditionAggregates[] = unserialize($condition);
            }
        }

        if (empty($conditionAggregates)) {
            return $conditionContentBag;
        }

        foreach ($conditionAggregates as $conditionAggregate) {
            $field = $conditionAggregate->getField();
            $condition = $conditionAggregate->getCondition();

            $actualValue = $this->conditionValueProvider->getValueByField($field);

            if ($actualValue === null) {
                continue;
            }

            $conditionContentId = $this->applyCondition($userId, $actualValue, $conditionAggregate);

            if ($conditionContentId === null) {
                continue;
            }

            $conditionContentBag->put($condition->getId(), $conditionContentId);
        }

        return $conditionContentBag;
    }

    public function applyCondition(
        int $userId,
        string $actualValue,
        ConditionCombinationOperatorFieldAggregate $conditionAggregate
    ): ?int{
        $field = $conditionAggregate->getField();
        $condition = $conditionAggregate->getCondition();
        $conditionCombinations = $conditionAggregate->getCombinations();

        if (empty($conditionCombinations)) {
            return $condition->getDefaultContentId();
        }

        foreach ($conditionCombinations as $conditionCombination) {
            $operator = $conditionCombination->getOperator();
            $value = $conditionCombination->getConditionCombination()->getValue();

            $assertion = $this->getOperatorAssertion($operator);

            if (!$assertion->match($value, $actualValue)) {
                continue;
            }

            if ($userId === 0) {
                $this->conditionsStatisticsManager->incrementConditionStatistics($condition, $field);
            }

            return $conditionCombination->getConditionCombination()->getContentId();
        }

        return $condition->getDefaultContentId();
    }

    private function getOperatorAssertion(Operator $operator): Assert
    {
        $assertionClass = $operator->getClass();

        return new $assertionClass();
    }
}
