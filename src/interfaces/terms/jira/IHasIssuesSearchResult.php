<?php
namespace extas\interfaces\terms\jira;

use extas\interfaces\jira\results\issues\ISearchResult;

/**
 * Interface IHasIssuesSearchResult
 *
 * @package extas\interfaces\terms\jira
 * @author jeyroik <jeyroik@gmail.com>
 */
interface IHasIssuesSearchResult
{
    public const FIELD__ISSUES_SEARCH_RESULT = 'issues_search_result';

    /**
     * @param array $args
     * @return ISearchResult|null
     */
    public function getIssuesSearchResult(array $args): ?ISearchResult;

    /**
     * @param array $issues
     * @param array $names
     * @param array $schema
     * @param array $args
     * @return array
     */
    public function setIssuesSearchResult(
        array $issues,
        array $names = [],
        array $schema = [],
        array $args = []
    ): array
}
