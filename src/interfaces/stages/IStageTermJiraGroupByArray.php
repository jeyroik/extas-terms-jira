<?php
namespace extas\interfaces\stages;

use extas\interfaces\jira\issues\IIssue;

/**
 * Interface IStageTermJiraGroupByArray
 *
 * @package extas\interfaces\stages
 * @author jeyroik <jeyroik@gmail.com>
 */
interface IStageTermJiraGroupByArray
{
    public const NAME = 'extas.term.jira.grouped.by.array';

    /**
     * @param array $groupedBy
     * @param array $index
     * @param IIssue $issue
     * @return array $groupedBy with appended index
     */
    public function __invoke(array $groupedBy, array $index, IIssue $issue): array;
}
