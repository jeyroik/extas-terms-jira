<?php
namespace extas\components\terms\jira;

use extas\interfaces\terms\ITerm;

/**
 * Class TotalByField
 *
 * @package extas\components\terms\jira
 * @author jeyroik <jeyroik@gmail.com>
 */
class TotalByField extends JiraTermCalculator
{
    use THasIssuesSearchResult;

    public const TERM_PARAM__MARKER = 'jira__by_field_sum';
    public const TERM_PARAM__FIELD_NAME = 'field_name';
    public const TERM_PARAM__SUB_FIELD_NAME = 'sub_field_name';

    protected string $marker = self::TERM_PARAM__MARKER;

    /**
     * @param ITerm $term
     * @param array $args
     * @return int|mixed|null
     */
    protected function execute(ITerm $term, array $args)
    {
        $fieldName = $term->getParameterValue(static::TERM_PARAM__FIELD_NAME, '');
        $subfield = $term->getParameterValue(static::TERM_PARAM__SUB_FIELD_NAME, '');
        $subfieldMethod = $subfield ? 'getField' . ucfirst($subfield) : 'getFieldValue';
        $issues = $this->getIssues($args);
        $total = 0;

        foreach ($issues as $issue) {
            if ($issue->hasField($fieldName)) {
                $field = $issue->getField($fieldName);
                $total += $field->$subfieldMethod();
            }
        }

        return $total;
    }
}
