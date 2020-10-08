<?php
namespace extas\components\terms\jira;

use extas\components\terms\TermCalculator;
use extas\interfaces\http\IHasHttpIO;
use extas\interfaces\IHasName;
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
abstract class JiraTermCalculator extends TermCalculator implements IStageTermJiraAfterCalculate
{
    use THasIssuesSearchResult;

    public const TERM_PARAM__DO_RUN_BEFORE_STAGE = 'do_run_before_stage';
    public const TERM_PARAM__DO_RUN_AFTER_STAGE = 'do_run_after_stage';
    public const TERM_PARAM__CALCULATION_MARKER = 'calculation_marker';

    public const PARAM__MARKER = 'marker';
    public const RESULT__SOURCE = 'source';

    /**
     * @var string
     */
    protected string $marker = '';

    /**
     * @param $result
     * @param $term
     * @param $args
     */
    public function __invoke(&$result, $term, $args): void
    {
        $marker = $this->getParameterValue(static::PARAM__MARKER, $this->marker);

        $term = $this->stabelizeTermMarker($term);
        $termMarker = $term->getParameterValue(static::TERM_PARAM__CALCULATION_MARKER);

        if (strpos($termMarker, $marker) === false) {
            $result = $this->stabelizeResult($result);
            $index = $this->getParameterValue(IHasName::FIELD__NAME, $this->marker);

            $term->setParameterValue(static::TERM_PARAM__CALCULATION_MARKER, $termMarker .= $marker);

            $result[$index] = $this->calculateTerm($term, $args);
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
     * @return ITerm
     */
    protected function stabelizeTermMarker(ITerm $term): ITerm
    {
        if (!$term->hasParameter(static::TERM_PARAM__CALCULATION_MARKER)) {
            $term->addParameterByValue(static::TERM_PARAM__CALCULATION_MARKER, '');
        }

        return $term;
    }

    /**
     * @param $result
     * @return array
     */
    protected function stabelizeResult($result): array
    {
        return is_array($result) ? $result : [static::RESULT__SOURCE => $result];
    }

    /**
     * @param ITerm $term
     * @param array $args
     * @return mixed
     */
    abstract protected function execute(ITerm $term, array $args);
}
