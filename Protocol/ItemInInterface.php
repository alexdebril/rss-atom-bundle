<?php

/**
 * Rss/Atom Bundle for Symfony.
 *
 *
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @copyright (c) 2013, Alexandre Debril
 */
namespace Debril\RssAtomBundle\Protocol;

use Debril\RssAtomBundle\Protocol\Parser\Media;

/**
 * interface used to represent incoming items
 * Interface ItemInInterface.
 */
interface ItemInInterface
{
    /**
     * Atom : feed.entry.title <feed><entry><title>
     * Rss  : rss.channel.item.title <rss><channel><item><title>.
     *
     * @param string $title
     * @deprecated removed in version 3.0
     */
    public function setTitle($title);

    /**
     * Atom : feed.entry.id <feed><entry><id>
     * Rss  : rss.channel.item.guid <rss><channel><item><guid>.
     *
     * @param string $id
     * @deprecated removed in version 3.0
     */
    public function setPublicId($id);

    /**
     * Atom : feed.entry.content <feed><entry><content>
     * Rss  : rss.channel.item.description <rss><channel><item><description>.
     *
     * @param string $description
     * @deprecated removed in version 3.0
     */
    public function setDescription($description);

    /**
     * Atom : feed.entry.summary <feed><entry><summary>.
     *
     * @param string $summary
     * @deprecated removed in version 3.0
     */
    public function setSummary($summary);

    /**
     * Atom : feed.entry.updated <feed><entry><updated>
     * Rss  : rss.channel.item.pubDate <rss><channel><item><pubDate>.
     *
     * @param \DateTime $updated
     * @deprecated removed in version 3.0
     */
    public function setUpdated(\DateTime $updated);

    /**
     * Atom : feed.entry.link <feed><entry><link>
     * Rss  : rss.channel.item.link <rss><channel><item><link>.
     *
     * @param string $link
     * @deprecated removed in version 3.0
     */
    public function setLink($link);

    /**
     * Atom : feed.entry.author.name <feed><entry><author><name>
     * Rss  : rss.channel.item.author <rss><channel><item><author>.
     *
     * @param string $author
     * @deprecated removed in version 3.0
     */
    public function setAuthor($author);

    /**
     * Rss  : rss.channel.item.comment <rss><channel><item><comment>.
     *
     * @param string $comment
     * @deprecated removed in version 3.0
     */
    public function setComment($comment);

    /**
     * Rss  : rss.channel.item.enclosure <rss><channel><item><enclosure>.
     *
     * @param Media $media
     * @deprecated removed in version 3.0
     */
    public function addMedia(Media $media);

    /**
     * Atom : feed.entry.category <feed><entry><category>
     * Rss  : rss.channel.item.category[term] <rss><channel><item><category>
     *
     * @param CategoryInInterface $category
     * @deprecated removed in version 3.0
     */
    public function addCategory(CategoryInInterface $category);
}
