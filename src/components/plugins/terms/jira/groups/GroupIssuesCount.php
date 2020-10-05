<?php
namespace extas\components\plugins\terms\jira\groups;

use extas\components\plugins\Plugin;
use extas\interfaces\IHasName;
use extas\interfaces\stages\IStageTermJiraGroupBy;
use extas\interfaces\terms\ITerm;

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
     * @param ITerm $term
     * @return array
     */
    public function __invoke(array &$groupedBy, array $result, ITerm $term): array
    {
        $curName = $this->getParameterValue(IHasName::FIELD__NAME, 'unknown');
        $result[static::FIELD__SELF_MARKER . '.' . $curName] = [];

        foreach ($groupedBy as $value => $issues) {
            $result[static::FIELD__SELF_MARKER . '.' . $curName][$value] = count($issues);
        }

        return $result;
    }
}
