<?php
namespace extas\components\terms\jira\results;

use extas\interfaces\terms\jira\results\IResultArray;

/**
 * Class ResultArray
 *
 * @package extas\components\terms\jira\results
 * @author jeyroik <jeyroik@gmail.com>
 */
class ResultArray extends CalculationResult implements IResultArray
{
    /**
     * @return array
     */
    public function export(): array
    {
        return $this->__toArray();
    }
}
