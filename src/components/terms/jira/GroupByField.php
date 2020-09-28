<?php
namespace extas\components\terms\jira;

use extas\components\terms\TermCalculator;
use extas\interfaces\jira\issues\IIssue;
use extas\interfaces\stages\IStageTermJiraGroupBy;
use extas\interfaces\terms\ITerm;

/**
 * Class GroupByField
 *
 * @package extas\components\terms\jira
 * @author jeyroik <jeyroik@gmail.com>
 */
class GroupByField extends TermCalculator
{
    use THasIssuesSearchResult;

    public const TERM_PARAM__MARKER = 'jira__group_by';
    public const TERM_PARAM__FIELD_NAME = 'field_name';
    public const TERM_PARAM__SUBFIELD_NAME = 'subfield_name';

    /**
     * @param ITerm $term
     * @param array $args
     * @return bool
     */
    public function canCalculate(ITerm $term, array $args = []): bool
    {
        return $term->getParameterValue(static::TERM_PARAM__MARKER, false);
    }

    /**
     * @param ITerm $term
     * @param array $args
     * @return array|mixed
     */
    public function calculateTerm(ITerm $term, array $args = [])
    {
        $result = $this->getIssuesSearchResult($args);
        $issues = $result->getIssues();
        $groupBy = $term->getParameterValue(static::TERM_PARAM__FIELD_NAME, '');
        $subfield = $term->getParameterValue(static::TERM_PARAM__SUBFIELD_NAME, '');
        $subfieldMethod = $subfield ? 'getField' . ucfirst($subfield) : 'getFieldValue';
        $groupedBy = [];

        foreach ($issues as $issue) {
            if (!$issue->hasField($groupBy)) {
                continue;
            }

            $groupedBy = $this->append($issue, $groupBy, $subfieldMethod, $groupedBy);
        }

        return $this->runStage($groupBy, $groupedBy, $args);
    }

    /**
     * @param string $groupBy
     * @param array $groupedBy
     * @param array $args
     * @return array
     */
    protected function runStage(string $groupBy, array $groupedBy, array $args): array
    {
        $result = [];

        foreach ($this->getPluginsByStage(IStageTermJiraGroupBy::NAME . '.' . $groupBy, $args) as $plugin) {
            /**
             * @var IStageTermJiraGroupBy $plugin
             */
            $result = $plugin($groupedBy, $result);
        }

        return $result;
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
