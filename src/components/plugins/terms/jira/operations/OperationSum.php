<?php
namespace extas\components\plugins\terms\jira\operations;

use extas\components\plugins\Plugin;
use extas\interfaces\stages\IStageTermJiraMathOperation;
use extas\interfaces\terms\ITerm;

/**
 * Class OperationSum
 *
 * @package extas\components\plugins\terms\jira\operations
 * @author jeyroik <jeyroik@gmail.com>
 */
class OperationSum extends OperationOnlyWithValues implements IStageTermJiraMathOperation
{
    public const OPERATION__NAME = 'sum';

    /**
     * @param array $values
     * @param ITerm $term
     * @return float
     */
    protected function calculate(array $values, ITerm $term): float
    {
        return array_sum($values);
    }
}
