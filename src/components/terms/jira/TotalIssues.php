<?php
namespace extas\components\terms\jira;

use extas\components\terms\jira\results\ResultNumeric;
use extas\interfaces\terms\ITerm;
use extas\interfaces\terms\jira\results\ICalculationResult;
use extas\interfaces\terms\jira\results\IResultArray;

/**
 * Class TotalIssues
 *
 * @package extas\components\terms\jira
 * @author jeyroik <jeyroik@gmail.com>
 */
class TotalIssues extends JiraTermCalculator
{
    use THasIssuesSearchResult;

    public const TERM_PARAM__MARKER = 'jira__total_issues';

    protected string $marker = self::TERM_PARAM__MARKER;
    protected string $argsInterface = IResultArray::class;

    /**
     * @param ITerm $term
     * @param array $issues
     * @return ICalculationResult
     */
    protected function execute(ITerm $term, array $issues): ICalculationResult
    {
        return new ResultNumeric([
            ResultNumeric::FIELD__NUMBER => count($issues)
        ]);
    }
}
