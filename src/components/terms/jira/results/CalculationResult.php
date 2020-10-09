<?php
namespace extas\components\terms\jira\results;

use extas\components\Item;
use extas\interfaces\terms\jira\results\ICalculationResult;

/**
 * Class CalculationResult
 *
 * @package extas\components\terms\jira\results
 * @author jeyroik <jeyroik@gmail.com>
 */
abstract class CalculationResult extends Item implements ICalculationResult
{
    /**
     * @return string
     */
    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
