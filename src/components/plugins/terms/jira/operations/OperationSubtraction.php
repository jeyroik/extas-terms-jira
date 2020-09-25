<?php
namespace extas\components\plugins\terms\jira\operations;

use extas\interfaces\stages\IStageTermJiraMathOperation;
use extas\interfaces\terms\ITerm;

/**
 * Class OperationSubtraction
 *
 * @package extas\components\plugins\terms\jira\operations
 * @author jeyroik <jeyroik@gmail.com>
 */
class OperationSubtraction extends OperationOnlyWithValues implements IStageTermJiraMathOperation
{
    public const OPERATION__NAME = 'sub';

    /**
     * @param array $values
     * @param ITerm $term
     * @return float
     */
    protected function calculate(array $values, ITerm $term): float
    {
        $result = array_shift($values);

        foreach ($values as $value) {
            $result -= $value;
        }

        return $result;
    }
}
