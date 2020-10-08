<?php
namespace extas\components\plugins\terms\jira\groups;

use extas\components\jira\results\issues\SearchResult;
use extas\components\plugins\Plugin;
use extas\components\terms\jira\MathOperations;
use extas\components\terms\jira\THasIssuesSearchResult;
use extas\interfaces\http\IHasHttpIO;
use extas\interfaces\IHasName;
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
    public function __invoke(array &$groupedBy, array $result, ITerm $term): array
    {
        $curName = $this->getParameterValue(IHasName::FIELD__NAME, 'unknown');
        $result[static::FIELD__SELF_MARKER . '.' . $curName] = [];

        $calculator = new MathOperations();
        $this->updateTermParams($term);

        foreach ($groupedBy as $value => $issues) {
            $args = [
                IHasHttpIO::FIELD__ARGUMENTS => [
                    IHasIssuesSearchResult::FIELD__ISSUES_SEARCH_RESULT => new SearchResult([
                        SearchResult::FIELD__ISSUES => $this->convertToArray($issues),
                        SearchResult::FIELD__IS_ENRICH_ISSUES => false
                    ])
                ]
            ];

            $result[static::FIELD__SELF_MARKER . '.' . $curName][$value] = $calculator->calculateTerm($term, $args);
        }

        return $result;
    }

    /**
     * @param ITerm $term
     */
    protected function updateTermParams(ITerm &$term): void
    {
        if (!$term->hasParameter(MathOperations::TERM_PARAM__MARKER)) {
            $term->addParameterByValue(MathOperations::TERM_PARAM__MARKER, true);
        }

        $pluginParams = $this->getParametersValues();
        foreach ($pluginParams as $name => $value) {
            $term->hasParameter($name)
                ? $term->setParameterValue($name, $value)
                : $term->addParameterByValue($name, $value);
        }
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
