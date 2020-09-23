<?php
namespace extas\components\plugins\terms\jira\operations;

use extas\components\plugins\Plugin;
use extas\interfaces\stages\IStageTermJiraMathOperation;
use extas\interfaces\terms\ITerm;

/**
 * Class OperationRoundResult
 *
 * @package extas\components\plugins\terms\jira\operations
 * @author jeyroik <jeyroik@gmail.com>
 */
class OperationRoundResult extends Plugin implements IStageTermJiraMathOperation
{
    public const OPERATION__NAME = 'round';

    public const TERM_PARAM__ROUND_PRECISION = 'round_precision';

    /**
     * @param array $values
     * @param ITerm $term
     * @param float $result
     * @return float
     */
    public function __invoke(array $values, ITerm $term, float $result): float
    {
        $precision = $term->getParameterValue(static::TERM_PARAM__ROUND_PRECISION, 0);

        return round($result, $precision);
    }
}
