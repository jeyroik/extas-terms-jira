<?php
namespace extas\components\terms\jira;

use extas\components\terms\TermCalculator;
use extas\interfaces\IItem;
use extas\interfaces\jira\issues\fields\IStatus;
use extas\interfaces\jira\issues\fields\IStatusCategory;
use extas\interfaces\terms\ITerm;

/**
 * Class DoneIssues
 *
 * @deprecated Please, use GroupByField instead
 * @package extas\components\terms\jira
 * @author jeyroik <jeyroik@gmail.com>
 */
class ByStatusIssuesCount extends TermCalculator
{
    use THasIssuesSearchResult;

    public const TERM_NAME__COUNT_BY_STATUS = 'jira__count_by_status';
    public const TERM_PARAM__STATUS_VARIANTS = 'status_variants';
    public const PARAM__STATUS_FIELD_NAME = 'field_name';
    public const PARAM__CATEGORY_FIELD_NAME = 'category_field_name';

    /**
     * @var array [status => int $count]
     */
    protected static array $byStatusCount = [];
    protected static string $hash = '';

    /**
     * @param ITerm $term
     * @param array $args
     * @return bool
     */
    public function canCalculate(ITerm $term, array $args = []): bool
    {
        return $term->getParameterValue(static::TERM_NAME__COUNT_BY_STATUS, false)
            && $this->getIssuesSearchResult($args);
    }

    /**
     * @param ITerm $term
     * @param array $args
     * @return int|mixed
     */
    public function calculateTerm(ITerm $term, array $args = [])
    {
        $this->initCounter($args);
        $count = 0;
        $statusVariants = $term->getParameterValue(static::TERM_PARAM__STATUS_VARIANTS, []);

        foreach ($statusVariants as $variant) {
            if (isset(static::$byStatusCount[$variant])) {
                $count += static::$byStatusCount[$variant];
            }
        }

        return $count;
    }

    public function resetCount(): void
    {
        static::$byStatusCount = [];
    }

    /**
     * @param array $args
     * @return bool
     */
    protected function initCounter(array $args): bool
    {
        $this->runtimeReset();

        if (!empty(static::$byStatusCount)) {
            return true;
        }

        $result = $this->getIssuesSearchResult($args);
        $issues = $result->getIssues();
        $statusFieldName = $this->getParameterValue(static::PARAM__STATUS_FIELD_NAME, '');
        $statusFieldMethod = 'getField' . ucfirst($statusFieldName);

        foreach ($issues as $issue) {
            /**
             * @var IStatus $status
             */
            $status = $issue->getField(IStatus::NAME);
            $statusMarker = $this->convertStatusCategory($status->$statusFieldMethod());

            if (!isset(static::$byStatusCount[$statusMarker])) {
                static::$byStatusCount[$statusMarker] = 0;
            }

            static::$byStatusCount[$statusMarker]++;
        }

        return true;
    }

    protected function runtimeReset(): void
    {
        $hash = $this->getParameterValue(static::PARAM__STATUS_FIELD_NAME, '')
            . '.' . $this->getParameterValue(static::PARAM__CATEGORY_FIELD_NAME, '<skiped>');

        if (static::$hash != $hash) {
            static::$hash = $hash;
            $this->resetCount();
        }
    }

    /**
     * @param IStatusCategory|mixed $category
     * @return mixed
     */
    protected function convertStatusCategory($category)
    {
        $categoryFieldName = $this->getParameterValue(static::PARAM__CATEGORY_FIELD_NAME, '');
        $method = 'getField' . ucfirst($categoryFieldName);

        if (!$category instanceof IItem || !$category->hasMethod($method)) {
            return $category;
        }

        return $category->$method();
    }
}
