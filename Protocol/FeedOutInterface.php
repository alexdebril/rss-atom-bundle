<?php

/**
 * Rss/Atom Bundle for Symfony.
 *
 *
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @copyright (c) 2013, Alexandre Debril
 */
namespace Debril\RssAtomBundle\Protocol;

/**
 * Interface used to send a RSS/ATOM stream to Formatter classes.
 */
/**
 * Interface FeedOutInterface.
 */
interface FeedOutInterface
{
    /**
     * Atom : feed.updated <feed><updated>
     * Rss  : rss.channel.lastBuildDate <rss><channel><lastBuildDate>.
     *
     * @return \DateTime
     */
    public function getLastModified();

    /**
     * Atom : feed.title <feed><title>
     * Rss  : rss.channel.title <rss><channel><title>.
     *
     * @return string
     */
    public function getTitle();

    /**
     * Atom : feed.subtitle <feed><subtitle>
     * Rss  : rss.channel.description <rss><channel><description>.
     *
     * @return string
     */
    public function getDescription();

    /**
     * Atom : feed.link <feed><link>
     * Rss  : rss.channel.link <rss><channel><link>.
     *
     * @return string
     */
    public function getLink();

    /**
     * Atom : feed.id <feed><id>
     * Rss  : rss.channel.id <rss><channel><id>.
     *
     * @return string
     */
    public function getPublicId();

    /**
     * Atom : feed.entry <feed><entry>
     * Rss  : rss.channel.item <rss><channel><item>.
     *
     * @return ItemOutInterface[]
     */
    public function getItems();
}
