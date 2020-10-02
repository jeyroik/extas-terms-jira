<?php
namespace extas\components\plugins\terms\jira\groups;

use extas\components\jira\results\issues\SearchResult;
use extas\components\plugins\Plugin;
use extas\components\terms\jira\MathOperations;
use extas\components\terms\jira\THasIssuesSearchResult;
use extas\components\terms\Term;
use extas\interfaces\http\IHasHttpIO;
use extas\interfaces\samples\parameters\ISampleParameter;
use extas\interfaces\stages\IStageTermJiraGroupBy;
use extas\interfaces\terms\ITerm;
use extas\interfaces\terms\jira\IHasIssuesSearchResult;

/**
 * Class GroupMathOperations
 *
 * @package extas\components\plugins\terms\jira\groups
 * @author jeyroik <jeyroik@gmail.com>
 */
class GroupMathOperations extends Plugin implements IStageTermJiraGroupBy
{
    use THasIssuesSearchResult;

    public const FIELD__SELF_MARKER = '__math__';

    /**
     * @param array $groupedBy
     * @param array $result
     * @param ITerm $term
     * @return array
     */
    public function __invoke(array $groupedBy, array $result, ITerm $term): array
    {
        $issuesResult = $this->getIssuesSearchResult($this->__toArray());
        $result[static::FIELD__SELF_MARKER] = [];

        $calculator = new MathOperations();
        if (!$term->hasParameter(MathOperations::TERM_PARAM__MARKER)) {
            $term->addParameterByValue(MathOperations::TERM_PARAM__MARKER, true);
        }
        $term->addParametersByValues($this->getParametersValues());

        foreach ($groupedBy as $value => $issues) {
            $args = [
                IHasHttpIO::FIELD__ARGUMENTS => [
                    IHasIssuesSearchResult::FIELD__ISSUES_SEARCH_RESULT => new SearchResult([
                        SearchResult::FIELD__ISSUES => $this->convertToArray($issues),
                        SearchResult::FIELD__NAMES => $issuesResult->getNames(),
                        SearchResult::FIELD__SCHEMA => $this->convertToArray($issuesResult->getSchema()),
                        SearchResult::FIELD__IS_ENRICH_ISSUES => false
                    ])
                ]
            ];

            $result[static::FIELD__SELF_MARKER][$value] = $calculator->calculateTerm($term, $args);
        }

        return $result;
    }

    /**
     * @param array $items
     * @return array
     */
    protected function convertToArray(array $items): array
    {
        foreach ($items as $index => $item) {
            $items[$index] = $item->__toArray();
        }

        return $items;
    }
}
