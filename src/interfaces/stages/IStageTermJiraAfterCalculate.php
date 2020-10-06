<?php
namespace extas\interfaces\stages;

use extas\interfaces\terms\ITerm;

/**
 * Interface IStageTermJiraAfterCalculate
 *
 * @package extas\interfaces\stages
 * @author jeyroik <jeyroik@gmail.com>
 */
interface IStageTermJiraAfterCalculate
{
    public const NAME = 'extas.term.jira.after.calculate';

    /**
     * @param mixed $result
     * @param ITerm $term
     * @param array $args
     */
    public function __invoke(&$result, ITerm $term, array $args): void;
}
