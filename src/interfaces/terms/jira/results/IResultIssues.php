<?php
namespace extas\interfaces\terms\jira\results;

use extas\interfaces\jira\issues\IIssue;

/**
 * Interface IResultIssues
 *
 * @package extas\interfaces\terms\jira\results
 * @author jeyroik <jeyroik@gmail.com>
 */
interface IResultIssues extends IResultArray
{
    public const FIELD__ISSUES = 'issues';

    /**
     * @return array
     */
    public function export(): array;

    /**
     * @return IIssue[]
     */
    public function getIssues(): array;

    /**
     * @param IIssue[] $issues
     * @return IResultIssues
     */
    public function setIssues(array $issues): IResultIssues;
}
