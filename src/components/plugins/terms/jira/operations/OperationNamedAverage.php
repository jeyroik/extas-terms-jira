<?php
namespace extas\components\plugins\terms\jira\operations;

use extas\interfaces\stages\IStageTermJiraMathOperation;
use extas\interfaces\terms\ITerm;
use MathPHP\Statistics\Average;

/**
 * Class OperationNamedAverage
 *
 * @package extas\components\plugins\terms\jira\operations
 * @author jeyroik <jeyroik@gmail.com>
 */
class OperationNamedAverage extends OperationOnlyWithValues implements IStageTermJiraMathOperation
{
    public const OPERATION__NAME = 'named_average';

    /**
     * @value see Average static methods list
     */
    public const TERM_PARAM__FUNCTION_NAME = 'named_average__function';
    public const TERM_PARAM__FUNCTION_ARGS = 'named_average__args';

    /**
     * @param array $values
     * @param ITerm $term
     * @return float
     */
    protected function calculate(array $values, ITerm $term): float
    {
        $functionName = $term->getParameterValue(static::TERM_PARAM__FUNCTION_NAME, '');

        if (!method_exists(Average::class, $functionName)) {
            return 0;
        }

        $args = $term->getParameterValue(static::TERM_PARAM__FUNCTION_ARGS, []);
        array_unshift($args, $values);

        return Average::$functionName(...$args);
    }
}
