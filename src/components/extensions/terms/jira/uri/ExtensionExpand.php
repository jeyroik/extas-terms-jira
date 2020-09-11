<?php
namespace extas\components\extensions\terms\jira\uri;

use extas\components\extensions\Extension;
use extas\interfaces\extensions\terms\jira\uri\IExtensionExpand;
use extas\interfaces\terms\jira\IUri;

/**
 * Class ExtensionExpand
 *
 * @package extas\components\extensions\terms\jira\uri
 * @author jeyroik <jeyroik@gmail.com>
 */
class ExtensionExpand extends Extension implements IExtensionExpand
{
    /**
     * @param array $expands
     * @param IUri|null $uri
     * @return IUri|null
     */
    public function expand(array $expands, IUri $uri = null)
    {
        $uri->add('expand', implode(',', $expands));

        return $uri;
    }
}
