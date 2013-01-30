<?php
/**
 * Rss/Atom Bundle for Symfony 2
 *
 * @package RssBundle\Protocol
 *
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @copyright (c) 2013, Alexandre Debril
 * 
 */

namespace Debril\RssBundle\Protocol;

class FeedContent implements \Iterator
{

    /**
     *
     * @var array[\Debril\RssBundle\Protocol\Item]
     */
    protected $items = array();

    /**
     *
     * @var \Datetime
     */
    protected $lastModified;

    /**
     *
     * @var array
     */
    protected $headers;

    /**
     *
     * @var string
     */
    protected $title;

    /**
     *
     * @var string
     */
    protected $subtitle;

    /**
     *
     * @var string
     */
    protected $link;

    /**
     *
     * @var string
     */
    protected $id;

    /**
     *
     * @return \DateTime
     */
    public function getLastModified()
    {
        return $this->lastModified;
    }

    /**
     *
     * @param \DateTime $lastModified
     */
    public function setLastModified(\DateTime $lastModified)
    {
        $this->lastModified = $lastModified;
    }

    /**
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     *
     * @param array $headers
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;
    }

    /**
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     *
     * @param string $title
     * @return \Debril\RssBundle\Protocol\FeedContent
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     *
     * @param string $subtitle
     * @return \Debril\RssBundle\Protocol\FeedContent
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     *
     * @param string $link
     * @return \Debril\RssBundle\Protocol\FeedContent
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @param string $id
     * @return \Debril\RssBundle\Protocol\FeedContent
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     *
     * @return int
     */
    public function getItemsCount()
    {
        return count($this->items);
    }

    /**
     * @param \Debril\RssBundle\Protocol\Item $item
     * @return FeedContent
     */
    public function addItem(\Debril\RssBundle\Protocol\Item $item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     *
     * @return \Debril\RssBundle\Protocol\Item
     */
    public function current()
    {
        return current($this->items);
    }

    /**
     *
     * @return int
     */
    public function key()
    {
        return key($this->items);
    }

    /**
     *
     * @return \Debril\RssBundle\Protocol\Item
     */
    public function next()
    {
        return next($this->items);
    }

    /**
     *
     * @return \Debril\RssBundle\Protocol\FeedContent
     */
    public function rewind()
    {
        reset($this->items);

        return $this;
    }

    /**
     *
     * @return boolean
     */
    public function valid()
    {
        return $this->current() instanceof \Debril\RssBundle\Protocol\Item;
    }

}