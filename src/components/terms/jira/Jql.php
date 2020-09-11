<?php
namespace extas\components\terms\jira;

use extas\components\Item;
use extas\interfaces\terms\jira\IJql;

/**
 * Class Jql
 *
 * @package extas\components\terms\jira
 * @author jeyroik <jeyroik@gmail.com>
 */
class Jql extends Item implements IJql
{
    /**
     * @return string
     */
    public function __toString(): string
    {
        return '(' . implode(') and (', $this->getQuery()) . ')';
    }

    /**
     * @param string $fieldName
     * @param string $condition
     * @param string $value
     * @return $this
     */
    public function andCondition(string $fieldName, string $condition, string $value)
    {
        $query = $this->getQuery();
        $query[] = $fieldName . ' ' . $condition . ' ' . $value;
        $this->config[static::FIELD__QUERY] = $query;

        return $this;
    }

    /**
     * @return array
     */
    public function getQuery(): array
    {
        return $this->config[static::FIELD__QUERY] ?? '';
    }

    /**
     * @return string
     */
    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
