<?php
namespace tests\terms\jira\misc;

use extas\components\plugins\Plugin;
use extas\interfaces\stages\IStageTermJiraAfterCalculate;
use extas\interfaces\terms\ITerm;

/**
 * Class PluginAfterCalculate
 *
 * @package tests\terms\jira\misc
 * @author jeyroik <jeyroik@gmail.com>
 */
class PluginAfterCalculate extends Plugin implements IStageTermJiraAfterCalculate
{
    /**
     * @param mixed $result
     * @param ITerm $term
     * @param array $args
     */
    public function __invoke(&$result, ITerm $term, array $args): void
    {
        $result += 10;
    }
}
