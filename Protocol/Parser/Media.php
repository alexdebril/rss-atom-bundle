<?php

/**
 * Rss/Atom Bundle for Symfony.
 *
 *
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @copyright (c) 2013, Alexandre Debril
 */
namespace Debril\RssAtomBundle\Protocol\Parser;

/**
 * class Media.
 * @deprecated removed in version 3.0
 */
class Media
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var int
     */
    protected $length;

    /**
     * @return string
     * @deprecated removed in version 3.0
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return $this
     * @deprecated removed in version 3.0
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     * @deprecated removed in version 3.0
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return $this
     * @deprecated removed in version 3.0
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     * @deprecated removed in version 3.0
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @param string $length
     *
     * @return $this
     * @deprecated removed in version 3.0
     */
    public function setLength($length)
    {
        $this->length = intval($length);

        return $this;
    }
}
