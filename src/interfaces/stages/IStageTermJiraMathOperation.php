<?php
namespace extas\interfaces\stages;

use extas\interfaces\terms\ITerm;

/**
 * Interface IStageTermJiraMatOperation
 *
 * @package extas\interfaces\stages
 * @author jeyroik <jeyroik@gmail.com>
 */
interface IStageTermJiraMathOperation
{
    public const NAME = 'extas.term.jira.math.operation';

    /**
     * @param array $values
     * @param ITerm $term
     * @param float $result
     * @return float
     */
    public function __invoke(array $values, ITerm $term, float $result): float;
}
