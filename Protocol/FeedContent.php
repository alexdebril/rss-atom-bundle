<?php
/**
 * Rss/Atom Bundle for Symfony 2
 *
 * @package RssAtomBundle\Protocol
 *
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @copyright (c) 2013, Alexandre Debril
 *
 */

namespace Debril\RssAtomBundle\Protocol;

use Debril\RssAtomBundle\Protocol\Item;
use Debril\RssAtomBundle\Protocol\FeedContentException;

class FeedContent implements \Iterator
{

    /**
     *
     * @var array[\Debril\RssAtomBundle\Protocol\Item]
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

        return $this;
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
     * @return \Debril\RssAtomBundle\Protocol\FeedContent
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
     * @return \Debril\RssAtomBundle\Protocol\FeedContent
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
     * @return \Debril\RssAtomBundle\Protocol\FeedContent
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
     * @return \Debril\RssAtomBundle\Protocol\FeedContent
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
     *
     * @param \Debril\RssAtomBundle\Protocol\Item $item
     * @param \DateTime $startDate
     * @return \Debril\RssAtomBundle\Protocol\FeedContent
     * @throws FeedContentException
     */
    public function addAcceptableItem(Item $item, \DateTime $startDate)
    {
        if ( $item->getUpdated() instanceof \DateTime )
        {
            $interval = $startDate->diff($item->getUpdated());

            if ($interval->invert === 0 )
                $this->addItem ($item);
        }
        else
            throw new FeedContentException("tried to add an item without date");

        return $this;
    }

    /**
     * @param \Debril\RssAtomBundle\Protocol\Item $item
     * @return FeedContent
     */
    public function addItem(Item $item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     *
     * @return \Debril\RssAtomBundle\Protocol\Item
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
     * @return \Debril\RssAtomBundle\Protocol\Item
     */
    public function next()
    {
        return next($this->items);
    }

    /**
     *
     * @return \Debril\RssAtomBundle\Protocol\FeedContent
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
        return $this->current() instanceof \Debril\RssAtomBundle\Protocol\Item;
    }

}