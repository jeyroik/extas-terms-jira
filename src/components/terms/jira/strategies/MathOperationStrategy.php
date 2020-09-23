<?php
namespace extas\components\terms\jira\strategies;

use extas\components\Item;
use extas\interfaces\terms\jira\strategies\IMathOperationStrategy;

/**
 * Class MatOperationStrategy
 *
 * @package extas\components\terms\jira\strategies
 * @author jeyroik <jeyroik@gmail.com>
 */
abstract class MathOperationStrategy extends Item implements IMathOperationStrategy
{
    /**
     * @return string
     */
    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
