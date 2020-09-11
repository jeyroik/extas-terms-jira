<?php
namespace extas\interfaces\extensions\terms\jira\uri;

use extas\interfaces\terms\jira\IUri;

/**
 * Interface IExtensionExpand
 *
 * @package extas\interfaces\extensions\terms\jira\uri
 * @author jeyroik <jeyroik@gmail.com>
 */
interface IExtensionExpand
{
    /**
     * @param array $expands
     * @return IUri
     */
    public function expand(array $expands);
}
