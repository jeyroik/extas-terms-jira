<?php
namespace extas\components\terms\jira;

use extas\components\terms\jira\results\ResultNumeric;
use extas\interfaces\terms\ITerm;
use extas\interfaces\terms\jira\results\ICalculationResult;
use extas\interfaces\terms\jira\results\IResultIssues;

/**
 * Class TotalByField
 *
 * @package extas\components\terms\jira
 * @author jeyroik <jeyroik@gmail.com>
 */
class TotalByField extends JiraTermCalculator
{
    use THasIssuesSearchResult;
    use THasFieldSubfield;

    public const TERM_PARAM__MARKER = 'jira__by_field_sum';
    public const TERM_PARAM__FIELD_NAME = 'field_name';
    public const TERM_PARAM__SUB_FIELD_NAME = 'subfield_name';

    protected string $marker = self::TERM_PARAM__MARKER;
    protected string $argsInterface = IResultIssues::class;

    /**
     * @param ITerm $term
     * @param array $issues
     * @return ICalculationResult
     */
    protected function execute(ITerm $term, array $issues): ICalculationResult
    {
        $fieldName = $this->getField($term);
        $subfieldMethod = $this->getSubfieldMethod($term);
        $total = 0;

        foreach ($issues as $issue) {
            if ($issue->hasField($fieldName)) {
                $field = $issue->getField($fieldName);
                $total += $field->$subfieldMethod();
            }
        }

        return new ResultNumeric([
            ResultNumeric::FIELD__NUMBER => $total
        ]);
    }
}
