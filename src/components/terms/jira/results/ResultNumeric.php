<?php
namespace extas\components\terms\jira\results;

use extas\interfaces\terms\jira\results\IResultNumeric;

/**
 * Class ResultNumeric
 *
 * @package extas\components\terms\jira\results
 * @author jeyroik <jeyroik@gmail.com>
 */
class ResultNumeric extends CalculationResult implements IResultNumeric
{
    /**
     * @return int|float
     */
    public function export()
    {
        return $this->getNumber();
    }

    /**
     * @return float|int|mixed
     */
    public function getNumber()
    {
        return $this->config[static::FIELD__NUMBER] ?? 0;
    }

    /**
     * @param $number
     * @return IResultNumeric
     */
    public function setNumber($number): IResultNumeric
    {
        $this->config[static::FIELD__NUMBER] = is_numeric($number) ? $number : 0;

        return $this;
    }
}
