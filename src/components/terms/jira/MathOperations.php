<?php
namespace extas\components\terms\jira;

use extas\components\exceptions\MissedOrUnknown;
use extas\components\Item;
use extas\components\THasClass;
use extas\interfaces\IHasClass;
use extas\interfaces\terms\ITerm;
use extas\interfaces\terms\jira\results\ICalculationResult;
use extas\interfaces\terms\jira\results\IResultIssues;
use extas\interfaces\terms\jira\strategies\IMathOperationStrategy;

/**
 * Class MathOperations
 *
 * @package extas\components\terms\jira
 * @author jeyroik <jeyroik@gmail.com>
 */
class MathOperations extends JiraTermCalculator
{
    use THasIssuesSearchResult;

    /**
     * @value true|false
     */
    public const TERM_PARAM__MARKER = 'jira__math_operation';

    /**
     * @value [field1, field2, ...]
     */
    public const TERM_PARAM__FIELDS = 'fields';

    /**
     * @value [field1 => subfield1, field2 => subfield2, ...]
     */
    public const TERM_PARAM__SUBFIELDS = 'subfields';

    /**
     * @value any string
     */
    public const TERM_PARAM__OPERATION = 'operation';

    /**
     * @value class or alias
     */
    public const TERM_PARAM__STRATEGY = 'strategy';

    protected string $marker = self::TERM_PARAM__MARKER;
    protected string $argsInterface = IResultIssues::class;

    /**
     * @param ITerm $term
     * @param array $issues
     * @return ICalculationResult
     * @throws MissedOrUnknown
     */
    protected function execute(ITerm $term, array $issues): ICalculationResult
    {
        /**
         * @var IMathOperationStrategy $strategy
         */

        $strategyClass = $term->getParameterValue(static::TERM_PARAM__STRATEGY, '');

        if (!$strategyClass) {
            throw new MissedOrUnknown('strategy class');
        }

        $withClass = $this->getStrategyHolder($strategyClass);
        $strategy = $withClass->buildClassWithParameters();

        return $strategy($this, $term, $issues);
    }

    /**
     * @param string $strategyClass
     * @return IHasClass
     */
    protected function getStrategyHolder(string $strategyClass): IHasClass
    {
        return new class ([IHasClass::FIELD__CLASS => $strategyClass]) extends Item implements IHasClass {
            use THasClass;

            protected function getSubjectForExtension(): string
            {
                return 'extas.term.jira.math.operation.strategy.holder';
            }
        };
    }
}
