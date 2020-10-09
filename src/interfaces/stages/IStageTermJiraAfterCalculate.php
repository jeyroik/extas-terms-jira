<?php
namespace extas\interfaces\stages;

use extas\interfaces\terms\ITerm;
use extas\interfaces\terms\jira\results\ICalculationResult;

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
     * @param ICalculationResult $result
     * @param ITerm $term
     * @param array $args
     */
    public function __invoke(ICalculationResult &$result, ITerm $term, array $args): void;
}
