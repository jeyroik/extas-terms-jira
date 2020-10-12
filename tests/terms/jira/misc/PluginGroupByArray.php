<?php
namespace tests\terms\jira\misc;

use extas\components\plugins\Plugin;
use extas\interfaces\jira\issues\IIssue;
use extas\interfaces\stages\IStageTermJiraGroupByArray;

/**
 * Class PluginGroupByArray
 *
 * Parse string like `Role: 10100 (jeyroik)`
 *
 * @package tests\terms\jira\misc
 * @author jeyroik <jeyroik@gmail.com>
 */
class PluginGroupByArray extends Plugin implements IStageTermJiraGroupByArray
{
    /**
     * @param array $groupedBy
     * @param array $index
     * @param IIssue $issue
     * @return array
     */
    public function __invoke(array $groupedBy, array $index, IIssue $issue): array
    {
        foreach ($index as $item) {
            preg_match('/(\d+)\s+\((.*?)\)/i', $item, $matches);
            if (isset($matches[2])) {
                $groupedBy = $this->append($groupedBy, $matches[1], $matches[2], $issue);
            }
        }

        return $groupedBy;
    }

    /**
     * @param array $groupedBy
     * @param int $role
     * @param string $username
     * @param IIssue $issue
     * @return array
     */
    protected function append(array $groupedBy, int $role, string $username, IIssue $issue): array
    {
        $index = $username . '_' . $role;

        if (!isset($groupedBy[$index])) {
            $groupedBy[$index] = [];
        }

        $groupedBy[$index][] = $issue;

        return $groupedBy;
    }
}
