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
 * interface used to represent incoming items
 */
interface ItemIn extends ItemOut
{

    /**
     * Atom : feed.entry.title <feed><entry><title>
     * Rss  : rss.channel.item.title <rss><channel><item><title>
     * @param string $title
     */
    public function setTitle($title);

    /**
     * Atom : feed.entry.id <feed><entry><id>
     * Rss  : rss.channel.item.guid <rss><channel><item><guid>
     * @param string $id
     */
    public function setPublicId($id);

    /**
     * Atom : feed.entry.content <feed><entry><content>
     * Rss  : rss.channel.item.description <rss><channel><item><description>
     * @param string $description
     */
    public function setDescription($description);

    /**
     * Atom : feed.entry.summary <feed><entry><summary>
     * @param string $summary
     */
    public function setSummary($summary);

    /**
     * Atom : feed.entry.updated <feed><entry><updated>
     * Rss  : rss.channel.item.pubDate <rss><channel><item><pubDate>
     * @param \DateTime $updated
     */
    public function setUpdated(\DateTime $updated);

    /**
     * Atom : feed.entry.link <feed><entry><link>
     * Rss  : rss.channel.item.link <rss><channel><item><link>
     * @param string $link
     */
    public function setLink($link);

    /**
     * Atom : feed.entry.author.name <feed><entry><author><name>
     * Rss  : rss.channel.item.author <rss><channel><item><author>
     * @param string $author
     */
    public function setAuthor($author);

    /**
     * Rss  : rss.channel.item.comment <rss><channel><item><comment>
     * @param string $comment
     */
    public function setComment($comment);
}
