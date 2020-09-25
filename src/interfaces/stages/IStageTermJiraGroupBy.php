<?php
namespace extas\interfaces\stages;

/**
 * Interface IStageTermJiraGroupBy
 *
 * @package extas\interfaces\stages
 * @author jeyroik <jeyroik@gmail.com>
 */
interface IStageTermJiraGroupBy
{
    public const NAME = 'extas.term.jira.group.by';

    /**
     * @param array $groupedBy [string fieldValue => array issues, ...]
     * @param array $result
     * @return array
     */
    public function __invoke(array $groupedBy, array $result): array;
}
