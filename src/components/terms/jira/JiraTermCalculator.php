<?php
namespace extas\components\terms\jira;

use extas\components\terms\TermCalculator;
use extas\interfaces\http\IHasHttpIO;
use extas\interfaces\jira\issues\IIssue;
use extas\interfaces\terms\ITerm;

/**
 * Class JiraTermCalculator
 *
 * @package extas\components\terms\jira
 * @author jeyroik <jeyroik@gmail.com>
 */
abstract class JiraTermCalculator extends TermCalculator
{
    use THasIssuesSearchResult;

    /**
     * @var string
     */
    protected string $marker = '';

    /**
     * @param ITerm $term
     * @param array $args
     * @return bool
     */
    public function canCalculate(ITerm $term, array $args = []): bool
    {
        return (bool) $term->getParameterValue($this->marker, false);
    }

    /**
     * @param array $args
     * @return IIssue[]
     */
    protected function getIssues(array $args): array
    {
        return isset($args[IHasHttpIO::FIELD__ARGUMENTS])
            ? $this->getIssuesSearchResult($args)->getIssues()
            : $args;
    }
}
