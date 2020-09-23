<?php
namespace extas\components\terms\jira\strategies;

use extas\components\terms\jira\MathOperations;
use extas\components\terms\jira\MatOperations;
use extas\components\terms\jira\THasIssuesSearchResult;
use extas\interfaces\extensions\jira\fields\IHasFieldValue;
use extas\interfaces\jira\issues\fields\IField;
use extas\interfaces\jira\issues\IIssue;
use extas\interfaces\stages\IStageTermJiraMathOperation;
use extas\interfaces\terms\ITerm;
use extas\interfaces\terms\ITermCalculator;

/**
 * Class MatOperationCross
 *
 * 1. Берёт указанные поля из одного тикета, проводит операцию OPERATION между ними.
 * 2. Берёт указанные поля из следующего тикета, проводит операцию OPERATION между ними.
 * 3. Берёт результаты из первого тикета и следующих, проводит операцию CROSS OPERATION между ними.
 *
 * @package extas\components\terms\jira\strategies
 * @author jeyroik <jeyroik@gmail.com>
 */
class MathOperationCross extends MathOperationStrategy
{
    use THasIssuesSearchResult;

    public const TERM_PARAM__CROSS_OPERATION = 'operation_cross';

    /**
     * @param ITermCalculator $calculator
     * @param ITerm $term
     * @param array $args
     * @return mixed|void
     */
    public function __invoke(ITermCalculator $calculator, ITerm $term, array $args)
    {
        $operation = $term->getParameterValue(MathOperations::TERM_PARAM__OPERATION, '');
        $fieldsNames = $term->getParameterValue(MathOperations::TERM_PARAM__FIELDS, []);
        $subfields = $term->getParameterValue(MathOperations::TERM_PARAM__SUBFIELDS, []);

        $result = $this->getIssuesSearchResult($args);
        $issues = $result->getIssues();

        $forCrossOperation = [];

        foreach ($issues as $issue) {
            $forOperation = $this->getIssueFields($issue, $fieldsNames, $subfields);
            $forCrossOperation[] = $this->runOperationStage(
                $operation,
                $term,
                $forOperation
            );
        }

        return $this->runOperationStage(
            $term->getParameterValue(static::TERM_PARAM__CROSS_OPERATION, ''),
            $term,
            $forCrossOperation
        );
    }

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
