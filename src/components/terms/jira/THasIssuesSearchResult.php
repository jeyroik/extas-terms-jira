<?php
namespace extas\components\terms\jira;

use extas\components\jira\results\issues\SearchResult;
use extas\interfaces\http\IHasHttpIO;
use extas\interfaces\jira\results\issues\ISearchResult;
use extas\interfaces\terms\jira\IHasIssuesSearchResult;

/**
 * Trait THasIssuesSearchResult
 *
 * @package extas\components\terms\jira
 * @author jeyroik <jeyroik@gmail.com>
 */
trait THasIssuesSearchResult
{
    /**
     * @param array $args
     * @return ISearchResult|null
     */
    public function getIssuesSearchResult(array $args): ?ISearchResult
    {
        $arguments = $args[IHasHttpIO::FIELD__ARGUMENTS] ?? [];

        return $arguments[IHasIssuesSearchResult::FIELD__ISSUES_SEARCH_RESULT] ?? null;
    }

    /**
     * @param array $issues
     * @param array $names
     * @param array $schema
     * @param array $args
     * @return array $args with issues search result in it
     */
    public function setIssuesSearchResult(
        array $issues,
        array $names = [],
        array $schema = [],
        array $args = []
    ): array
    {
        $args[IHasHttpIO::FIELD__ARGUMENTS] = $args[IHasHttpIO::FIELD__ARGUMENTS] ?? [];
        $args[IHasHttpIO::FIELD__ARGUMENTS][IHasIssuesSearchResult::FIELD__ISSUES_SEARCH_RESULT] = new SearchResult([
            SearchResult::FIELD__ISSUES => $issues,
            SearchResult::FIELD__NAMES => $names,
            SearchResult::FIELD__SCHEMA => $schema
        ]);

        return $args;
    }
}
