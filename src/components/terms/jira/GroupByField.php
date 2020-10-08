<?php
namespace extas\components\terms\jira;

use extas\components\terms\jira\results\ResultArray;
use extas\components\terms\jira\results\ResultIssues;
use extas\interfaces\jira\issues\IIssue;
use extas\interfaces\stages\IStageTermJiraGroupBy;
use extas\interfaces\terms\ITerm;
use extas\interfaces\terms\jira\results\ICalculationResult;
use extas\interfaces\terms\jira\results\IResultArray;
use extas\interfaces\terms\jira\results\IResultIssues;

/**
 * Class GroupByField
 *
 * @package extas\components\terms\jira
 * @author jeyroik <jeyroik@gmail.com>
 */
class GroupByField extends JiraTermCalculator
{
    use THasIssuesSearchResult;
    use THasFieldSubfield;

    public const TERM_PARAM__MARKER = 'jira__group_by';
    public const TERM_PARAM__FIELD_NAME = 'field_name';
    public const TERM_PARAM__SUBFIELD_NAME = 'subfield_name';
    public const TERM_PARAM__DO_RUN_STAGE = 'do_run_stage';

    protected string $marker = self::TERM_PARAM__MARKER;
    protected string $argsInterface = IResultArray::class;

    /**
     * @param ITerm $term
     * @param array $issues
     * @return ICalculationResult
     */
    protected function execute(ITerm $term, array $issues): ICalculationResult
    {
        $groupBy = $this->getField($term);
        $subfieldMethod = $this->getSubfieldMethod($term);
        $groupedBy = [];

        foreach ($issues as $issue) {
            if (!$issue->hasField($groupBy)) {
                continue;
            }

            $groupedBy = $this->append($issue, $groupBy, $subfieldMethod, $groupedBy);
        }

        $result = new ResultIssues([
            ResultIssues::FIELD__ISSUES => $groupedBy
        ]);

        return $term->getParameterValue(static::TERM_PARAM__DO_RUN_STAGE, true)
            ? $this->runStage($groupBy, $result, $issues, $term)
            : $result;
    }

    /**
     * @param string $groupBy
     * @param IResultIssues $groupedBy
     * @param array $args
     * @param ITerm $term
     * @return ICalculationResult
     */
    protected function runStage(
        string $groupBy,
        IResultIssues $groupedBy,
        array $args,
        ITerm $term
    ): ICalculationResult
    {
        $groupedBy = $groupedBy->export();
        $result = [];

        foreach ($this->getPluginsByStage(IStageTermJiraGroupBy::NAME . '.' . $groupBy, $args) as $plugin) {
            /**
             * @var IStageTermJiraGroupBy $plugin
             */
            $result = $plugin($groupedBy, $result, $term);
        }

        return new ResultArray($result);
    }

    /**
     * @param IIssue $issue
     * @param string $groupBy
     * @param string $subfieldMethod
     * @param array $groupedBy
     * @return array
     */
    protected function append(IIssue $issue, string $groupBy, string $subfieldMethod, array $groupedBy): array
    {
        $field = $issue->getField($groupBy);
        $index = $field->$subfieldMethod();

        if (!isset($groupedBy[$index])) {
            $groupedBy[$index] = [];
        }

        $groupedBy[$index][] = $issue;

        return $groupedBy;
    }
}
