<?php
namespace extas\components\terms\jira\strategies;

use extas\interfaces\jira\issues\fields\IField;
use extas\interfaces\jira\issues\IIssue;
use extas\interfaces\stages\IStageTermJiraMathOperation;
use extas\interfaces\terms\ITerm;

/**
 * Trait TMathOperation
 *
 * @method getPluginsByStage(string $stage, array $args = [])
 *
 * @package extas\components\terms\jira\strategies
 * @author jeyroik <jeyroik@gmail.com>
 */
trait TMathOperation
{
    /**
     * @param string $operationName
     * @param ITerm $term
     * @param array $values
     * @return float
     */
    protected function runOperationStage(string $operationName, ITerm $term, array $values): float
    {
        $result = 0;

        foreach ($this->getPluginsByStage(IStageTermJiraMathOperation::NAME . '.' . $operationName) as $plugin) {
            /**
             * @var IStageTermJiraMathOperation $plugin
             */
            $result = $plugin($values, $term, $result);
        }

        return $result;
    }

    /**
     * @param IIssue $issue
     * @param array $fields
     * @param array $subfields
     * @return array
     */
    protected function getIssueFields(IIssue $issue, array $fields, array $subfields): array
    {
        $result = [];

        foreach ($fields as $fieldName) {
            if (!$issue->hasField($fieldName)) {
                continue;
            }

            $field = $issue->getField($fieldName);
            $result[] = $this->getFieldSubfield($field, $subfields);
        }

        return $result;
    }

    /**
     * @param IField|IHasFieldValue $field
     * @param array $subfields
     * @return mixed
     */
    protected function getFieldSubfield(IField $field, array $subfields)
    {
        $fieldName = $field->getName();

        if (isset($subfields[$fieldName])) {
            $subFieldMethod = 'getField' . ucfirst($subfields[$fieldName]);
            return $field->$subFieldMethod();
        }

        return $field->getFieldValue();
    }
}