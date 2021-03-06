<?php
namespace extas\interfaces\terms\jira\strategies;

use extas\interfaces\IItem;
use extas\interfaces\jira\issues\IIssue;
use extas\interfaces\terms\ITerm;
use extas\interfaces\terms\ITermCalculator;
use extas\interfaces\terms\jira\results\ICalculationResult;

/**
 * Interface IMatOperationStrategy
 *
 * @package extas\interfaces\terms\jira
 * @author jeyroik <jeyroik@gmail.com>
 */
interface IMathOperationStrategy extends IItem
{
    public const SUBJECT = 'extas.term.jira.strategy.math.operation';

    /**
     * @param ITermCalculator $calculator
     * @param ITerm $term
     * @param IIssue[] $issues
     * @return ICalculationResult
     */
    public function __invoke(ITermCalculator $calculator, ITerm $term, array $issues): ICalculationResult;
}
