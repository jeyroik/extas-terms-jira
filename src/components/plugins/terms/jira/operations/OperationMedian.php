<?php
namespace extas\components\plugins\terms\jira\operations;

use extas\interfaces\stages\IStageTermJiraMathOperation;
use extas\interfaces\terms\ITerm;
use MathPHP\Exception\BadDataException;
use MathPHP\Exception\OutOfBoundsException;
use MathPHP\Statistics\Average;

/**
 * Class OperationMedian
 *
 * @package extas\components\plugins\terms\jira\operations
 * @author jeyroik <jeyroik@gmail.com>
 */
class OperationMedian extends OperationOnlyWithValues implements IStageTermJiraMathOperation
{
    public const OPERATION__NAME = 'median';

    /**
     * @param array $values
     * @param ITerm $term
     * @return float
     * @throws BadDataException
     * @throws OutOfBoundsException
     */
    protected function calculate(array $values, ITerm $term): float
    {
        return Average::median($values);
    }
}
