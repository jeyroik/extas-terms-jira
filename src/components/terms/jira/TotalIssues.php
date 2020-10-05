<?php
namespace extas\components\terms\jira;

use extas\interfaces\terms\ITerm;

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

    /**
     * @param ITerm $term
     * @param array $args
     * @return int|mixed
     */
    public function calculateTerm(ITerm $term, array $args = [])
    {
        return count($this->getIssues($args));
    }
}
