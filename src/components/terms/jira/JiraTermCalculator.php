<?php
namespace extas\components\terms\jira;

use extas\components\terms\TermCalculator;
use extas\interfaces\http\IHasHttpIO;
use extas\interfaces\jira\issues\IIssue;
use extas\interfaces\stages\IStageTermJiraAfterCalculate;
use extas\interfaces\stages\IStageTermJiraBeforeCalculate;
use extas\interfaces\terms\ITerm;

/**
 * Class JiraTermCalculator
 *
 * @package extas\components\terms\jira
 * @author jeyroik <jeyroik@gmail.com>
 */
abstract class JiraTermCalculator extends TermCalculator
{
    use THasIssuesSearchResult;

    public const TERM_PARAM__DO_RUN_BEFORE_STAGE = 'do_run_before_stage';
    public const TERM_PARAM__DO_RUN_AFTER_STAGE = 'do_run_after_stage';

    /**
     * @var string
     */
    protected string $marker = '';

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

        $result = $this->execute($term, $args);

        $term->getParameterValue(static::TERM_PARAM__DO_RUN_AFTER_STAGE, true)
        && $this->runAfterStage($result, $term, $args);

        return $result;
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
     * @param $result
     * @param ITerm $term
     * @param array $args
     */
    protected function runAfterStage(&$result, ITerm $term, array $args)
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
     * @param ITerm $term
     * @param array $args
     * @return mixed
     */
    abstract protected function execute(ITerm $term, array $args);

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
}
