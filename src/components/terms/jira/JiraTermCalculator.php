<?php
namespace extas\components\terms\jira;

use extas\components\terms\jira\results\ResultArray;
use extas\components\terms\TermCalculator;
use extas\interfaces\http\IHasHttpIO;
use extas\interfaces\IHasName;
use extas\interfaces\jira\issues\IIssue;
use extas\interfaces\stages\IStageTermJiraAfterCalculate;
use extas\interfaces\stages\IStageTermJiraBeforeCalculate;
use extas\interfaces\terms\ITerm;
use extas\interfaces\terms\jira\results\ICalculationResult;

/**
 * Class JiraTermCalculator
 *
 * @package extas\components\terms\jira
 * @author jeyroik <jeyroik@gmail.com>
 */
abstract class JiraTermCalculator extends TermCalculator implements IStageTermJiraAfterCalculate
{
    use THasIssuesSearchResult;

    public const TERM_PARAM__DO_RUN_BEFORE_STAGE = 'do_run_before_stage';
    public const TERM_PARAM__DO_RUN_AFTER_STAGE = 'do_run_after_stage';

    public const RESULT__SOURCE = 'source';

    /**
     * @var string
     */
    protected string $marker = '';
    protected string $argsInterface = ICalculationResult::class;

    /**
     * @param ICalculationResult $result
     * @param $term
     * @param $args
     */
    public function __invoke(ICalculationResult &$result, $term, $args): void
    {
        if ($result instanceof $this->argsInterface) {
            $index = $this->getParameterValue(IHasName::FIELD__NAME, $this->marker);
            $result = new ResultArray([
                static::RESULT__SOURCE => $result->export(),
                $index => $this->execute($term, $result->export())->export()
            ]);
        }
    }

    /**
     * @param ITerm $term
     * @param array $args
     * @return bool
     */
    public function canCalculate(ITerm $term, array $args = []): bool
    {
        return (bool) $term->getParameterValue($this->marker, false);
    }

    /**
     * @param ITerm $term
     * @param array $args
     * @return mixed
     */
    public function calculateTerm(ITerm $term, array $args = [])
    {
        $term->getParameterValue(static::TERM_PARAM__DO_RUN_BEFORE_STAGE, true)
        && $this->runBeforeStage($term, $args);

        $result = $this->execute($term, $this->getIssues($args));

        $term->getParameterValue(static::TERM_PARAM__DO_RUN_AFTER_STAGE, true)
        && $this->runAfterStage($result, $term, $args);

        return $result->export();
    }

    /**
     * @param ITerm $term
     * @param array $args
     */
    protected function runBeforeStage(ITerm &$term, array &$args)
    {
        $stage = IStageTermJiraBeforeCalculate::NAME . '.' . $this->marker;
        foreach ($this->getPluginsByStage($stage) as $plugin) {
            /**
             * @var IStageTermJiraBeforeCalculate $plugin
             */
            $plugin($term, $args);
        }
    }

    /**
     * @param ICalculationResult $result
     * @param ITerm $term
     * @param array $args
     */
    protected function runAfterStage(ICalculationResult &$result, ITerm $term, array $args)
    {
        $stage = IStageTermJiraAfterCalculate::NAME . '.' . $this->marker;
        foreach ($this->getPluginsByStage($stage) as $plugin) {
            /**
             * @var IStageTermJiraAfterCalculate $plugin
             */
            $plugin($result, $term, $args);
        }
    }

    /**
     * @param array $args
     * @return IIssue[]
     */
    protected function getIssues(array $args): array
    {
        return isset($args[IHasHttpIO::FIELD__ARGUMENTS])
            ? $this->getIssuesSearchResult($args)->getIssues()
            : $args;
    }

    /**
     * @param ITerm $term
     * @param array $args
     * @return ICalculationResult
     */
    abstract protected function execute(ITerm $term, array $args): ICalculationResult;
}
