<?php
namespace tests\terms\jira\misc;

use extas\components\plugins\Plugin;
use extas\interfaces\stages\IStageTermJiraBeforeCalculate;
use extas\interfaces\terms\ITerm;

/**
 * Class PluginBeforeCalculate
 *
 * @package tests\terms\jira\misc
 * @author jeyroik <jeyroik@gmail.com>
 */
class PluginBeforeCalculate extends Plugin implements IStageTermJiraBeforeCalculate
{
    /**
     * @param ITerm $term
     * @param array $args
     */
    public function __invoke(ITerm &$term, array &$args): void
    {
        $term->addParameterByValue('before.worked', true);
    }
}
