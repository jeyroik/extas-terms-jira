<?php
namespace extas\interfaces\stages;

use extas\interfaces\terms\ITerm;

/**
 * Interface IStageTermJiraBeforeCalculate
 *
 * @package extas\interfaces\stages
 * @author jeyroik <jeyroik@gmail.com>
 */
interface IStageTermJiraBeforeCalculate
{
    public const NAME = 'extas.term.jira.before.calculate';

    /**
     * @param ITerm $term
     * @param array $args
     */
    public function __invoke(ITerm &$term, array &$args): void;
}
