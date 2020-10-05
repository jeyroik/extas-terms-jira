<?php
namespace extas\components\terms\jira\strategies;

use extas\components\terms\jira\MathOperations;
use extas\components\terms\jira\THasIssuesSearchResult;
use extas\interfaces\jira\issues\IIssue;
use extas\interfaces\terms\ITerm;
use extas\interfaces\terms\ITermCalculator;

/**
 * Class MathOperationTotal
 *
 * 1. Берёт указанные поля из одного тикета, проводит операцию OPERATION между ними.
 * 2. Берёт результаты из первого тикета, поля из следующего, проводит операцию OPERATION между ними.
 *
 * @package extas\components\terms\jira\strategies
 * @author jeyroik <jeyroik@gmail.com>
 */
class MathOperationTotal extends MathOperationStrategy
{
    use THasIssuesSearchResult;
    use TMathOperation;

    public const TERM_PARAM__TOTAL_OPERATION = 'operation_total';

    /**
     * @param ITermCalculator $calculator
     * @param ITerm $term
     * @param IIssue[] $issues
     * @return mixed|void
     */
    public function __invoke(ITermCalculator $calculator, ITerm $term, array $issues)
    {
        $operation = $term->getParameterValue(MathOperations::TERM_PARAM__OPERATION, '');
        $fieldsNames = $term->getParameterValue(MathOperations::TERM_PARAM__FIELDS, []);
        $subfields = $term->getParameterValue(MathOperations::TERM_PARAM__SUBFIELDS, []);
        $total = 0;

        foreach ($issues as $issue) {
            $forOperation = $this->getIssueFields($issue, $fieldsNames, $subfields);
            $forOperation[] = $total;
            $total = $this->runOperationStage(
                $operation,
                $term,
                $forOperation
            );
        }

        return $total;
    }
}
