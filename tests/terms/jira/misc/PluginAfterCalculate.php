<?php
namespace tests\terms\jira\misc;

use extas\components\plugins\Plugin;
use extas\interfaces\stages\IStageTermJiraAfterCalculate;
use extas\interfaces\terms\ITerm;
use extas\interfaces\terms\jira\results\ICalculationResult;
use extas\interfaces\terms\jira\results\IResultNumeric;

/**
 * Class PluginAfterCalculate
 *
 * @package tests\terms\jira\misc
 * @author jeyroik <jeyroik@gmail.com>
 */
class PluginAfterCalculate extends Plugin implements IStageTermJiraAfterCalculate
{
    /**
     * @param ICalculationResult $result
     * @param ITerm $term
     * @param array $args
     */
    public function __invoke(ICalculationResult &$result, ITerm $term, array $args): void
    {
        if ($result instanceof IResultNumeric) {
            $result->setNumber($result->getNumber() + 10);
        }
    }
}
