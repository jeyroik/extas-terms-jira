<?php
namespace extas\interfaces\stages;

use extas\interfaces\terms\ITerm;

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
     * @param ITerm $term
     * @return array
     */
    public function __invoke(array &$groupedBy, array $result, ITerm $term): array;
}
