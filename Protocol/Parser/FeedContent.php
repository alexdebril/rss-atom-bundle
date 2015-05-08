<?php

/**
 * Rss/Atom Bundle for Symfony 2.
 *
 *
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @copyright (c) 2013, Alexandre Debril
 */
namespace Debril\RssAtomBundle\Protocol\Parser;

use Debril\RssAtomBundle\Protocol\FeedInterface;
use Debril\RssAtomBundle\Protocol\ItemIn;

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
 * $feed->setDescription('the description');
 * $feed->addItem($item);
 * </code>
 */

/**
 * Class FeedContent.
 */
class FeedContent implements FeedInterface
{
    /**
     * Atom : feed.entry <feed><entry>
     * Rss  : rss.channel.item <rss><channel><item>.
     *
     * @var array[\Debril\RssAtomBundle\Protocol\Parser\Item]
     */
    protected $items = array();

    /**
     * Atom : feed.updated <feed><updated>
     * Rss  : rss.channel.lastBuildDate <rss><channel><lastBuildDate>
     *   or   rss.channel.pubDate <rss><channel><pubDate>.
     *
     * @var \Datetime
     */
    protected $lastModified;

    /**
     * Atom : feed.title <feed><title>
     * Rss  : rss.channel.title <rss><channel><title>.
     *
     * @var string
     */
    protected $title;

    /**
     * Atom : feed.subtitle <feed><subtitle>
     * Rss  : rss.channel.description <rss><channel><description>.
     *
     * @var string
     */
    protected $description;

    /**
     * Atom : feed.link <feed><link>
     * Rss  : rss.channel.link <rss><channel><link>.
     *
     * @var string
     */
    protected $link;

    /**
     * Atom : feed.id <feed><id>
     * Rss  : rss.channel.id <rss><channel><id>.
     *
     * @var string
     */
    protected $publicId;

    /**
     * Atom : feed.updated <feed><updated>
     * Rss  : rss.channel.lastBuildDate <rss><channel><lastBuildDate>
     *   or   rss.channel.pubDate <rss><channel><pubDate>.
     *
     * @return \DateTime
     */
    public function getLastModified()
    {
        return $this->lastModified;
    }

    /**
     * Atom : feed.updated <feed><updated>
     * Rss  : rss.channel.lastBuildDate <rss><channel><lastBuildDate>
     *   or   rss.channel.pubDate <rss><channel><pubDate>.
     *
     * @param \DateTime $lastModified
     *
     * @return $this
     */
    public function setLastModified(\DateTime $lastModified)
    {
        $this->lastModified = $lastModified;

        return $this;
    }

    /**
     * Atom : feed.title <feed><title>
     * Rss  : rss.channel.title <rss><channel><title>.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Atom : feed.title <feed><title>
     * Rss  : rss.channel.title <rss><channel><title>.
     *
     * @param string $title
     *
     * @return \Debril\RssAtomBundle\Protocol\Parser\FeedContent
     */
    public function setTitle($title)
    {
        $this->title = (string) $title;

        return $this;
    }

    /**
     * Atom : feed.subtitle <feed><subtitle>
     * Rss  : rss.channel.description <rss><channel><description>.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Atom : feed.subtitle <feed><subtitle>
     * Rss  : rss.channel.description <rss><channel><description>.
     *
     * @param string $description
     *
     * @return \Debril\RssAtomBundle\Protocol\Parser\FeedContent
     */
    public function setDescription($description)
    {
        $this->description = (string) $description;

        return $this;
    }

    /**
     * Atom : feed.link <feed><link>
     * Rss  : rss.channel.link <rss><channel><link>.
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Atom : feed.link <feed><link>
     * Rss  : rss.channel.link <rss><channel><link>.
     *
     * @param string $link
     *
     * @return \Debril\RssAtomBundle\Protocol\Parser\FeedContent
     */
    public function setLink($link)
    {
        $this->link = (string) $link;

        return $this;
    }

    /**
     * Atom : feed.id <feed><id>
     * Rss  : rss.channel.id <rss><channel><id>.
     *
     * @return string
     */
    public function getPublicId()
    {
        return $this->publicId;
    }

    /**
     * Atom : feed.id <feed><id>
     * Rss  : rss.channel.id <rss><channel><id>.
     *
     * @param string $id
     *
     * @return \Debril\RssAtomBundle\Protocol\Parser\FeedContent
     */
    public function setPublicId($id)
    {
        $this->publicId = (string) $id;

        return $this;
    }

    /**
     * Number of feed.entry or rss.channel.item in the stream.
     *
     * @return int
     */
    public function getItemsCount()
    {
        return count($this->items);
    }

    /**
     * Atom : feed.entry <feed><entry>
     * Rss  : rss.channel.item <rss><channel><item>.
     *
     * @return array[\Debril\RssAtomBundle\Protocol\Item]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Atom : feed.entry <feed><entry>
     * Rss  : rss.channel.item <rss><channel><item>.
     *
     * @param \Debril\RssAtomBundle\Protocol\ItemIn $item
     *
     * @return FeedContent
     */
    public function addItem(ItemIn $item)
    {
        $this->items[] = $item;

        return $this;
    }
}
