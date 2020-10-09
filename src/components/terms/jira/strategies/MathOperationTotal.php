<?php
namespace extas\components\terms\jira\strategies;

use extas\components\terms\jira\MathOperations;
use extas\components\terms\jira\results\ResultNumeric;
use extas\components\terms\jira\THasIssuesSearchResult;
use extas\interfaces\jira\issues\IIssue;
use extas\interfaces\terms\ITerm;
use extas\interfaces\terms\ITermCalculator;
use extas\interfaces\terms\jira\results\ICalculationResult;

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

    /**
     * @param ITermCalculator $calculator
     * @param ITerm $term
     * @param IIssue[] $issues
     * @return ICalculationResult
     */
    public function __invoke(ITermCalculator $calculator, ITerm $term, array $issues): ICalculationResult
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

        return new ResultNumeric([
            ResultNumeric::FIELD__NUMBER => $total
        ]);
    }
}
