<?php
namespace extas\components\terms\jira;

use extas\components\terms\TermCalculator;
use extas\interfaces\terms\ITerm;

/**
 * Class TotalByField
 *
 * @package extas\components\terms\jira
 * @author jeyroik <jeyroik@gmail.com>
 */
class TotalByField extends TermCalculator
{
    use THasIssuesSearchResult;

    public const TERM_PARAM__MARKER = 'jira__by_field_sum';
    public const TERM_PARAM__FIELD_NAME = 'field_name';
    public const TERM_PARAM__SUB_FIELD_NAME = 'sub_field_name';

    /**
     * @param ITerm $term
     * @param array $args
     * @return bool
     */
    public function canCalculate(ITerm $term, array $args = []): bool
    {
        return (bool) $term->getParameterValue(static::TERM_PARAM__MARKER, false);
    }

    /**
     * @param ITerm $term
     * @param array $args
     * @return int|mixed
     */
    public function calculateTerm(ITerm $term, array $args = [])
    {
        $fieldName = $term->getParameterValue(static::TERM_PARAM__FIELD_NAME, '');
        $subfield = $term->getParameterValue(static::TERM_PARAM__SUB_FIELD_NAME, '');
        $subfieldMethod = $subfield ? 'getField' . ucfirst($subfield) : 'getFieldValue';

        $result = $this->getIssuesSearchResult($args);
        $issues = $result->getIssues();
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
