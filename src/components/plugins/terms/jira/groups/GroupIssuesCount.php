<?php
namespace extas\components\plugins\terms\jira\groups;

use extas\components\plugins\Plugin;
use extas\interfaces\stages\IStageTermJiraGroupBy;

/**
 * Class GroupIssuesCount
 *
 * @package extas\components\plugins\terms\jira\groups
 * @author jeyroik <jeyroik@gmail.com>
 */
class GroupIssuesCount extends Plugin implements IStageTermJiraGroupBy
{
    public const FIELD__SELF_MARKER = '__count__';

    /**
     * @param array $groupedBy
     * @param array $result
     * @return array
     */
    public function __invoke(array $groupedBy, array $result): array
    {
        $result[static::FIELD__SELF_MARKER] = [];

        foreach ($groupedBy as $value => $issues) {
            $result[static::FIELD__SELF_MARKER][$value] = count($issues);
        }

        return $result;
    }
}