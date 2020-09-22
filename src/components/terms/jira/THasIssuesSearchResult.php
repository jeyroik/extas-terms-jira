<?php
namespace extas\components\terms\jira;

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
}
