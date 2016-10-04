<?php

/**
 * Rss/Atom Bundle for Symfony.
 *
 *
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @copyright (c) 2013, Alexandre Debril
 */
namespace Debril\RssAtomBundle\Protocol\Parser;

use DateTime;
use Debril\RssAtomBundle\Protocol\CategoryInInterface;
use Debril\RssAtomBundle\Protocol\ItemInInterface;
use Debril\RssAtomBundle\Protocol\ItemOutInterface;

/**
 * Class Item.
 * @deprecated removed in version 3.0
 */
class Item implements ItemInInterface, ItemOutInterface
{
    /**
     * Atom : feed.entry.title <feed><entry><title>
     * Rss  : rss.channel.item.title <rss><channel><item><title>.
     *
     * @var string
     */
    protected $title;

    /**
     * Atom : feed.entry.summary <feed><entry><summary>.
     *
     * @var string
     */
    protected $summary;

    /**
     * Atom : feed.entry.content <feed><entry><content>
     * Rss  : rss.channel.item.description <rss><channel><item><description>.
     *
     * @var string
     */
    protected $description;

    /**
     * Atom : feed.entry.updated <feed><entry><updated>
     * Rss  : rss.channel.item.pubDate <rss><channel><item><pubDate>.
     *
     * @var DateTime
     */
    protected $updated;

    /**
     * Atom : feed.entry.id <feed><entry><id>
     * Rss  : rss.channel.item.guid <rss><channel><item><guid>.
     *
     * @var string
     */
    protected $publicId;

    /**
     * Atom : feed.entry.link <feed><entry><link>
     * Rss  : rss.channel.item.link <rss><channel><item><link>.
     *
     * @var string
     */
    protected $link;

    /**
     * Atom : feed.entry.author.name <feed><entry><author><name>
     * Rss  : rss.channel.item.author <rss><channel><item><author>.
     *
     * @var string
     */
    protected $author;

    /**
     * Rss  : rss.channel.item.comment <rss><channel><item><comment>.
     *
     * @var string
     */
    protected $comment;

    /**
     * this will take all additional elements from other namespace as array with raw simpleXml
     * f.e. MediaRss or FeedBurner additions.
     *
     * @var array
     */
    protected $additional;

    /**
     * @var \ArrayIterator
     */
    protected $medias;

    /**
     * @var Category[]
     */
    protected $categories;

    /**
     * Constructor.
     * @deprecated removed in version 3.0
     */
    public function __construct()
    {
        $this->categories = array();
        $this->medias = new \ArrayIterator();
    }

    /**
     * Atom : feed.entry.title <feed><entry><title>
     * Rss  : rss.channel.item.title <rss><channel><item><title>.
     *
     * @return string
     * @deprecated removed in version 3.0
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Atom : feed.entry.title <feed><entry><title>
     * Rss  : rss.channel.item.title <rss><channel><item><title>.
     *
     * @param string $title
     *
     * @return Item
     * @deprecated removed in version 3.0
     */
    public function setTitle($title)
    {
        $this->title = (string) $title;

        return $this;
    }

    /**
     * Atom : feed.entry.id <feed><entry><id>
     * Rss  : rss.channel.item.guid <rss><channel><item><guid>.
     *
     * @return string
     * @deprecated removed in version 3.0
     */
    public function getPublicId()
    {
        return $this->publicId;
    }

    /**
     * Atom : feed.entry.id <feed><entry><id>
     * Rss  : rss.channel.item.guid <rss><channel><item><guid>.
     *
     * @param string $publicId
     *
     * @return Item
     * @deprecated removed in version 3.0
     */
    public function setPublicId($publicId)
    {
        $this->publicId = (string) $publicId;

        return $this;
    }

    /**
     * Atom : feed.entry.content <feed><entry><content>
     * Rss  : rss.channel.item.description <rss><channel><item><description>.
     *
     * @return string
     * @deprecated removed in version 3.0
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Atom : feed.entry.content <feed><entry><content>
     * Rss  : rss.channel.item.description <rss><channel><item><description>.
     *
     * @param string $description
     *
     * @return Item
     * @deprecated removed in version 3.0
     */
    public function setDescription($description)
    {
        $this->description = (string) $description;

        return $this;
    }

    /**
     * Atom : feed.entry.summary <feed><entry><summary>.
     *
     * @return string
     * @deprecated removed in version 3.0
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Atom : feed.entry.summary <feed><entry><summary>.
     *
     * @param string $summary
     *
     * @return Item
     * @deprecated removed in version 3.0
     */
    public function setSummary($summary)
    {
        $this->summary = (string) $summary;

        return $this;
    }

    /**
     * Atom : feed.entry.updated <feed><entry><updated>
     * Rss  : rss.channel.item.pubDate <rss><channel><item><pubDate>.
     *
     * @return \DateTime
     * @deprecated removed in version 3.0
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Atom : feed.entry.updated <feed><entry><updated>
     * Rss  : rss.channel.item.pubDate <rss><channel><item><pubDate>.
     *
     * @param \DateTime $updated
     *
     * @return Item
     * @deprecated removed in version 3.0
     */
    public function setUpdated(DateTime $updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Atom : feed.entry.link <feed><entry><link>
     * Rss  : rss.channel.item.link <rss><channel><item><link>.
     *
     * @return string
     * @deprecated removed in version 3.0
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Atom : feed.entry.link <feed><entry><link>
     * Rss  : rss.channel.item.link <rss><channel><item><link>.
     *
     * @param string $link
     *
     * @return Item
     * @deprecated removed in version 3.0
     */
    public function setLink($link)
    {
        $this->link = (string) $link;

        return $this;
    }

    /**
     * Atom : feed.entry.author.name <feed><entry><author><name>
     * Rss  : rss.channel.item.author <rss><channel><item><author>.
     *
     * @return string
     * @deprecated removed in version 3.0
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Atom : feed.entry.author.name <feed><entry><author><name>
     * Rss  : rss.channel.item.author <rss><channel><item><author>.
     *
     * @param string $author
     *
     * @return Item
     * @deprecated removed in version 3.0
     */
    public function setAuthor($author)
    {
        $this->author = (string) $author;

        return $this;
    }

    /**
     * Rss  : rss.channel.item.comment <rss><channel><item><comment>.
     *
     * @return string
     * @deprecated removed in version 3.0
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Rss  : rss.channel.item.comment <rss><channel><item><comment>.
     *
     * @param string $comment
     *
     * @return Item
     * @deprecated removed in version 3.0
     */
    public function setComment($comment)
    {
        $this->comment = (string) $comment;

        return $this;
    }

    /**
     * this will take all additional elements from other namespace as array with raw simpleXml
     * f.e. MediaRss or FeedBurner additions.
     *
     * @param array $additional
     * @deprecated removed in version 3.0
     */
    public function setAdditional(array $additional)
    {
        $this->additional = $additional;
    }

    /**
     * this will take all additional elements from other namespace as array with raw simpleXml
     * f.e. MediaRss or FeedBurner additions.
     *
     * @return array
     * @deprecated removed in version 3.0
     */
    public function getAdditional()
    {
        return $this->additional;
    }

    /**
     * @param Media $media
     *
     * @return $this
     * @deprecated removed in version 3.0
     */
    public function addMedia(Media $media)
    {
        $this->medias->append($media);

        return $this;
    }

    /**
     * @return \ArrayIterator
     * @deprecated removed in version 3.0
     */
    public function getMedias()
    {
        return $this->medias;
    }

    /**
     * @param CategoryInInterface $category
     *
     * @return $this
     * @deprecated removed in version 3.0
     */
    public function addCategory(CategoryInInterface $category)
    {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * @return Category[]
     * @deprecated removed in version 3.0
     */
    public function getCategories()
    {
        return $this->categories;
    }
}
