<?php
namespace extas\interfaces\terms\jira\results;

/**
 * Interface IResultNumeric
 *
 * @package extas\interfaces\terms\jira\results
 * @author jeyroik <jeyroik@gmail.com>
 */
interface IResultNumeric extends ICalculationResult
{
    public const FIELD__NUMBER = 'number';

    /**
     * @return int|float
     */
    public function getNumber();

    /**
     * @param $number
     * @return IResultNumeric
     */
    public function setNumber($number): IResultNumeric;
}
