<?php
namespace extas\components\terms\jira;

use extas\components\terms\TermCalculator;
use extas\interfaces\terms\ITerm;

/**
 * Class TotalIssues
 *
 * @package extas\components\terms\jira
 * @author jeyroik <jeyroik@gmail.com>
 */
class TotalIssues extends TermCalculator
{
    use THasIssuesSearchResult;

    public const TERM__TOTAL_ISSUES = 'jira__total_issues';

    /**
     * @param ITerm $term
     * @param array $args
     * @return bool
     */
    public function canCalculate(ITerm $term, array $args = []): bool
    {
        return $term->getName() == static::TERM__TOTAL_ISSUES && $this->getIssuesSearchResult($args);
    }

    /**
     * @param ITerm $term
     * @param array $args
     * @return int|mixed
     */
    public function calculateTerm(ITerm $term, array $args = [])
    {
        $issueResult = $this->getIssuesSearchResult($args);
        $issues = $issueResult->getIssues();

        return count($issues);
    }
}
