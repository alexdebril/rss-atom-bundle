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

/**
 * Item sent to Formatter classes
 */
interface ItemOut
{

    /**
     * Atom : feed.entry.title <feed><entry><title>
     * Rss  : rss.channel.item.title <rss><channel><item><title>
     * @return string
     */
    public function getTitle();

    /**
     * Atom : feed.entry.id <feed><entry><id>
     * Rss  : rss.channel.item.guid <rss><channel><item><guid>
     * @return string
     */
    public function getPublicId();

    /**
     * Atom : feed.entry.content <feed><entry><content>
     * Rss  : rss.channel.item.description <rss><channel><item><description>
     * @return string
     */
    public function getDescription();

    /**
     * Atom : feed.entry.summary <feed><entry><summary>
     * @return string
     */
    public function getSummary();

    /**
     * Atom : feed.entry.updated <feed><entry><updated>
     * Rss  : rss.channel.item.pubDate <rss><channel><item><pubDate>
     * @return \DateTime
     */
    public function getUpdated();

    /**
     * Atom : feed.entry.link <feed><entry><link>
     * Rss  : rss.channel.item.link <rss><channel><item><link>
     * @return string
     */
    public function getLink();

    /**
     * Atom : feed.entry.author.name <feed><entry><author><name>
     * Rss  : rss.channel.item.author <rss><channel><item><author>
     * @return string
     */
    public function getAuthor();

    /**
     * atom : feed.entry.link[rel="alternate"] <feed><entry><link rel="alternate" />
     * Rss  : rss.channel.item.comment <rss><channel><item><comment>
     * @return string
     */
    public function getComment();
}
