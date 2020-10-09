<?php
namespace extas\interfaces\terms\jira\results;

use extas\interfaces\IItem;

/**
 * Interface ICalculationResult
 *
 * @package extas\interfaces\terms\jira
 * @author jeyroik <jeyroik@gmail.com>
 */
interface ICalculationResult extends IItem
{
    public const SUBJECT = 'extas.term.jira.calculation.result';

    /**
     * @return mixed
     */
    public function export();
}
