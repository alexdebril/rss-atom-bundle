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

use \DateTime;
use \Debril\RssAtomBundle\Protocol\ItemIn;
use \Debril\RssAtomBundle\Protocol\ItemOut;

/**
 * Class Item
 * @deprecated will be removed in version 2.0
 * @package Debril\RssAtomBundle\Protocol\Parser
 */
class Item implements ItemIn, ItemOut
{

    /**
     * Atom : feed.entry.title <feed><entry><title>
     * Rss  : rss.channel.item.title <rss><channel><item><title>
     * @var string
     */
    protected $title;

    /**
     * Atom : feed.entry.summary <feed><entry><summary>
     * @var string
     */
    protected $summary;

    /**
     * Atom : feed.entry.content <feed><entry><content>
     * Rss  : rss.channel.item.description <rss><channel><item><description>
     * @var string
     */
    protected $description;

    /**
     * Atom : feed.entry.updated <feed><entry><updated>
     * Rss  : rss.channel.item.pubDate <rss><channel><item><pubDate>
     * @var DateTime
     */
    protected $updated;

    /**
     * Atom : feed.entry.id <feed><entry><id>
     * Rss  : rss.channel.item.guid <rss><channel><item><guid>
     * @var string
     */
    protected $publicId;

    /**
     * Atom : feed.entry.link <feed><entry><link>
     * Rss  : rss.channel.item.link <rss><channel><item><link>
     * @var string
     */
    protected $link;

    /**
     * Atom : feed.entry.author.name <feed><entry><author><name>
     * Rss  : rss.channel.item.author <rss><channel><item><author>
     * @var string
     */
    protected $author;

    /**
     * Rss  : rss.channel.item.comment <rss><channel><item><comment>
     * @var string
     */
    protected $comment;

    /**
     * this will take all additional elements from other namespace as array with raw simpleXml
     * f.e. MediaRss or FeedBurner additions
     * @var array
     */
    protected $additional;

    /**
     * Atom : feed.entry.title <feed><entry><title>
     * Rss  : rss.channel.item.title <rss><channel><item><title>
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Atom : feed.entry.title <feed><entry><title>
     * Rss  : rss.channel.item.title <rss><channel><item><title>
     * @param  string                                     $title
     * @return \Debril\RssAtomBundle\Protocol\Parser\Item
     */
    public function setTitle($title)
    {
        $this->title = (string) $title;

        return $this;
    }

    /**
     * Atom : feed.entry.id <feed><entry><id>
     * Rss  : rss.channel.item.guid <rss><channel><item><guid>
     * @return string
     */
    public function getPublicId()
    {
        return $this->publicId;
    }

    /**
     * Atom : feed.entry.id <feed><entry><id>
     * Rss  : rss.channel.item.guid <rss><channel><item><guid>
     * @param  string                                     $publicId
     * @return \Debril\RssAtomBundle\Protocol\Parser\Item
     */
    public function setPublicId($publicId)
    {
        $this->publicId = (string) $publicId;

        return $this;
    }

    /**
     * Atom : feed.entry.content <feed><entry><content>
     * Rss  : rss.channel.item.description <rss><channel><item><description>
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Atom : feed.entry.content <feed><entry><content>
     * Rss  : rss.channel.item.description <rss><channel><item><description>
     * @param  string                                     $description
     * @return \Debril\RssAtomBundle\Protocol\Parser\Item
     */
    public function setDescription($description)
    {
        $this->description = (string) $description;

        return $this;
    }

    /**
     * Atom : feed.entry.summary <feed><entry><summary>
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Atom : feed.entry.summary <feed><entry><summary>
     * @param  string                                     $summary
     * @return \Debril\RssAtomBundle\Protocol\Parser\Item
     */
    public function setSummary($summary)
    {
        $this->summary = (string) $summary;

        return $this;
    }

    /**
     * Atom : feed.entry.updated <feed><entry><updated>
     * Rss  : rss.channel.item.pubDate <rss><channel><item><pubDate>
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Atom : feed.entry.updated <feed><entry><updated>
     * Rss  : rss.channel.item.pubDate <rss><channel><item><pubDate>
     * @param  \DateTime                                  $updated
     * @return \Debril\RssAtomBundle\Protocol\Parser\Item
     */
    public function setUpdated(DateTime $updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Atom : feed.entry.link <feed><entry><link>
     * Rss  : rss.channel.item.link <rss><channel><item><link>
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Atom : feed.entry.link <feed><entry><link>
     * Rss  : rss.channel.item.link <rss><channel><item><link>
     * @param  string                                     $link
     * @return \Debril\RssAtomBundle\Protocol\Parser\Item
     */
    public function setLink($link)
    {
        $this->link = (string) $link;

        return $this;
    }

    /**
     * Atom : feed.entry.author.name <feed><entry><author><name>
     * Rss  : rss.channel.item.author <rss><channel><item><author>
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Atom : feed.entry.author.name <feed><entry><author><name>
     * Rss  : rss.channel.item.author <rss><channel><item><author>
     * @param  string                                     $author
     * @return \Debril\RssAtomBundle\Protocol\Parser\Item
     */
    public function setAuthor($author)
    {
        $this->author = (string) $author;

        return $this;
    }

    /**
     * Rss  : rss.channel.item.comment <rss><channel><item><comment>
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Rss  : rss.channel.item.comment <rss><channel><item><comment>
     * @param  string                                     $comment
     * @return \Debril\RssAtomBundle\Protocol\Parser\Item
     */
    public function setComment($comment)
    {
        $this->comment = (string) $comment;

        return $this;
    }

    /**
     * this will take all additional elements from other namespace as array with raw simpleXml
     * f.e. MediaRss or FeedBurner additions
     *
     * @param array $additional
     */
    public function setAdditional(array $additional)
    {
        $this->additional = $additional;
    }

    /**
     * this will take all additional elements from other namespace as array with raw simpleXml
     * f.e. MediaRss or FeedBurner additions
     *
     * @return array
     */
    public function getAdditional()
    {
        return $this->additional;
    }

}
