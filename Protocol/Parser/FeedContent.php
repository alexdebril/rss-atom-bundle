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

namespace Debril\RssAtomBundle\Protocol\Parser;

use Debril\RssAtomBundle\Protocol\Item;
use Debril\RssAtomBundle\Protocol\FeedContentException;

/**
 * A full Feed's content representation, containing both the headers of the feed
 * (not the HTTP one) and its news.
 *
 * This class is meant to be used reading or writing a feed. You will get
 * an instance of this class if you grab a RSS or ATOM feed over the internet.
 * You will also use this class to publish a feed.
 *
 * An example of reading implementation is given in the FeedReader's documentation.
 *
 * You can create a new feed as described below :
 *
 * <code>
 * $feed = new FeedContent;
 *
 * $feed->setLastModified($lastTimeANewsWasUpdated);
 *
 * $feed->setTitle('your feed title');
 * $feed->setSubTitle('the subtitle');
 * $feed->addItem($item);
 * </code>
 */
class FeedContent implements \Iterator, \Debril\RssAtomBundle\Protocol\FeedContent
{

    const XHTML = 'xhtml';
    const TEXT = 'text';

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
     * @var type
     */
    protected $contentType = self::TEXT;

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
        if ($item->getUpdated() instanceof \DateTime)
        {
            if ($item->getUpdated() > $startDate)
                $this->addItem($item);
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

    /**
     *
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     *
     * @param type $contentType
     * @return \Debril\RssAtomBundle\Protocol\FeedContent
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;

        return $this;
    }

}

