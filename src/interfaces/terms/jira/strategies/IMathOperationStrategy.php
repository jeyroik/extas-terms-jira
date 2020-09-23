<?php
namespace extas\interfaces\terms\jira\strategies;

use extas\interfaces\IItem;
use extas\interfaces\terms\ITerm;
use extas\interfaces\terms\ITermCalculator;

/**
 * Interface IMatOperationStrategy
 *
 * @package extas\interfaces\terms\jira
 * @author jeyroik <jeyroik@gmail.com>
 */
interface IMathOperationStrategy extends IItem
{
    public const SUBJECT = 'extas.term.jira.strategy.mat.operation';

    /**
     * @param ITermCalculator $calculator
     * @param ITerm $term
     * @param array $args
     * @return mixed
     */
    public function __invoke(ITermCalculator $calculator, ITerm $term, array $args);
}
