<?php
namespace extas\components\terms\jira;

use extas\components\Item;
use extas\components\THasPath;
use extas\interfaces\terms\jira\IUri;

/**
 * Class Uri
 *
 * @package extas\components\terms\jira
 * @author jeyroik <jeyroik@gmail.com>
 */
class Uri extends Item implements IUri
{
    use THasPath;

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getPath();
    }

    /**
     * @param string $name
     * @param string $value
     * @return $this|Uri
     */
    public function add(string $name, string $value)
    {
        $path = $this->getPath();
        $path .= '&' . $name . '=' . $value;
        $this->setPath($path);

        return $this;
    }

    /**
     * @return string
     */
    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
