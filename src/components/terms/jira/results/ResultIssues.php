<?php
namespace extas\components\terms\jira\results;

use extas\interfaces\terms\jira\results\IResultIssues;

/**
 * Class ResultIssues
 *
 * @package extas\components\terms\jira\results
 * @author jeyroik <jeyroik@gmail.com>
 */
class ResultIssues extends CalculationResult implements IResultIssues
{
    /**
     * @return array
     */
    public function export(): array
    {
        return $this->getIssues();
    }

    /**
     * @return array
     */
    public function getIssues(): array
    {
        return $this->config[static::FIELD__ISSUES] ?? [];
    }

    /**
     * @param array $issues
     * @return IResultIssues
     */
    public function setIssues(array $issues): IResultIssues
    {
        $this->config[static::FIELD__ISSUES] = $issues;

        return $this;
    }
}
