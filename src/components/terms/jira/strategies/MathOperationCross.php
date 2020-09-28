<?php
namespace extas\components\terms\jira\strategies;

use extas\components\terms\jira\MathOperations;
use extas\components\terms\jira\THasIssuesSearchResult;
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
    use TMathOperation;

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
}
