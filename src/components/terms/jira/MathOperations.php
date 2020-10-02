<?php
namespace extas\components\terms\jira;

use extas\components\Item;
use extas\components\terms\TermCalculator;
use extas\components\THasClass;
use extas\interfaces\IHasClass;
use extas\interfaces\terms\ITerm;
use extas\interfaces\terms\jira\strategies\IMathOperationStrategy;

/**
 * Class MathOperations
 *
 * @package extas\components\terms\jira
 * @author jeyroik <jeyroik@gmail.com>
 */
class MathOperations extends TermCalculator
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

    /**
     * @param ITerm $term
     * @param array $args
     * @return bool
     */
    public function canCalculate(ITerm $term, array $args = []): bool
    {
        return $term->getParameterValue(static::TERM_PARAM__MARKER, false);
    }

    /**
     * @param ITerm $term
     * @param array $args
     * @return mixed|null
     */
    public function calculateTerm(ITerm $term, array $args = [])
    {
        /**
         * @var IMathOperationStrategy $strategy
         */

        $strategyClass = $term->getParameterValue(static::TERM_PARAM__STRATEGY, '');

        if (!$strategyClass) {
            return null;
        }

        $withClass = $this->getStrategyHolder($strategyClass);
        $strategy = $withClass->buildClassWithParameters();

        return $strategy($this, $term, $args);
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
