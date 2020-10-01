<?php
namespace extas\components\plugins\terms\jira\groups;

use extas\components\jira\results\issues\SearchResult;
use extas\components\plugins\Plugin;
use extas\components\terms\jira\MathOperations;
use extas\components\terms\Term;
use extas\interfaces\http\IHasHttpIO;
use extas\interfaces\samples\parameters\ISampleParameter;
use extas\interfaces\stages\IStageTermJiraGroupBy;
use extas\interfaces\terms\jira\IHasIssuesSearchResult;

/**
 * Class GroupMathOperations
 *
 * @package extas\components\plugins\terms\jira\groups
 * @author jeyroik <jeyroik@gmail.com>
 */
class GroupMathOperations extends Plugin implements IStageTermJiraGroupBy
{
    public const FIELD__SELF_MARKER = '__math__';

    /**
     * @param array $groupedBy
     * @param array $result
     * @return array
     */
    public function __invoke(array $groupedBy, array $result): array
    {
        $result[static::FIELD__SELF_MARKER] = [];

        $calculator = new MathOperations();
        $term = new Term([
            Term::FIELD__PARAMETERS => [
                MathOperations::TERM_PARAM__MARKER => [
                    ISampleParameter::FIELD__NAME => MathOperations::TERM_PARAM__MARKER,
                    ISampleParameter::FIELD__VALUE => true
                ]
            ]
        ]);
        $term->addParametersByValues($this->getParametersValues());

        foreach ($groupedBy as $value => $issues) {
            $args = [
                IHasHttpIO::FIELD__ARGUMENTS => [
                    IHasIssuesSearchResult::FIELD__ISSUES_SEARCH_RESULT => new SearchResult([
                        SearchResult::FIELD__ISSUES => $issues
                    ])
                ]
            ];
            $result[static::FIELD__SELF_MARKER][$value] = $calculator->calculateTerm($term, $args);
        }

        return $result;
    }
}
