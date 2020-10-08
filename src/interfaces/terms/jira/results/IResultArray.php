<?php
namespace extas\interfaces\terms\jira\results;

/**
 * Interface IResultArray
 *
 * @package extas\interfaces\terms\jira\results
 * @author jeyroik <jeyroik@gmail.com>
 */
interface IResultArray extends ICalculationResult
{
    /**
     * @return array
     */
    public function export(): array;
}
