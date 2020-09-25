<?php
namespace extas\components\plugins\terms\jira\operations;

use extas\components\plugins\Plugin;
use extas\interfaces\stages\IStageTermJiraMathOperation;
use extas\interfaces\terms\ITerm;

/**
 * Class OperationOnlyWithValues
 *
 * @package extas\components\plugins\terms\jira\operations
 * @author jeyroik <jeyroik@gmail.com>
 */
abstract class OperationOnlyWithValues extends Plugin implements IStageTermJiraMathOperation
{
    /**
     * @param array $values
     * @param ITerm $term
     * @param float $result
     * @return float
     */
    public function __invoke(array $values, ITerm $term, float $result): float
    {
        if (empty($values) || $result) {
            return $result;
        }

        return $this->calculate($values, $term);
    }

    /**
     * @param array $values
     * @param ITerm $term
     * @return float
     */
    abstract protected function calculate(array $values, ITerm $term): float;
}
