<?php
namespace extas\interfaces\terms\jira;

use extas\interfaces\IHasPath;
use extas\interfaces\IItem;

/**
 * Interface IUri
 *
 * @package extas\interfaces\terms\jira
 * @author jeyroik <jeyroik@gmail.com>
 */
interface IUri extends IItem, IHasPath
{
    public const SUBJECT = 'extas.terms.jira.uri';

    /**
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function add(string $name, string $value);
}
