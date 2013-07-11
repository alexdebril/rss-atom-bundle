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

use Debril\RssAtomBundle\Protocol\ItemIn;

/**
 * interface used when reading an external feed
 */
interface FeedIn
{

    /**
     * Atom : feed.entry <feed><entry>
     * Rss  : rss.channel.entry <rss><channel><entry>
     * @param \Debril\RssAtomBundle\Protocol\ItemIn $item
     */
    public function addItem(ItemIn $item);

    /**
     * Atom : feed.entry <feed><entry>
     * Rss  : rss.channel.entry <rss><channel><entry>
     * @return array[\Debril\RssAtomBundle\Protocol\ItemIn]
     */
    public function getItems();

    /**
     * Atom : feed.updated <feed><updated>
     * Rss  : rss.channel.lastBuildDate <rss><channel><lastBuildDate>
     * @param \DateTime $lastModified
     */
    public function setLastModified(\DateTime $lastModified);

    /**
     * Atom : feed.title <feed><title>
     * Rss  : rss.channel.title <rss><channel><title>
     * @param string $title
     */
    public function setTitle($title);

    /**
     * Atom : feed.subtitle <feed><subtitle>
     * Rss  : rss.channel.description <rss><channel><description>
     * @param string $description
     */
    public function setDescription($description);

    /**
     * Atom : feed.link <feed><link>
     * Rss  : rss.channel.link <rss><channel><link>
     * @param string $link
     */
    public function setLink($link);

    /**
     * Atom : feed.id <feed><id>
     * Rss  : rss.channel.id <rss><channel><id>
     * @deprecated
     * @param mixed $id
     */
    public function setId($id);

    /**
     * Atom : feed.id <feed><id>
     * Rss  : rss.channel.id <rss><channel><id>
     * @param string $id
     */
    public function setPublicId($id);
}
