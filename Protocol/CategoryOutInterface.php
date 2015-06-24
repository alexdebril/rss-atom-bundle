<?php

/**
 * Rss/Atom Bundle for Symfony 2.
 *
 *
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @copyright (c) 2013, Alexandre Debril
 */
namespace Debril\RssAtomBundle\Protocol;

/**
 * Interface CategoryOutInterface
 */
interface CategoryOutInterface
{
    /**
     * Atom : feed.entry.category <feed><entry><category>
     * Rss  : rss.channel.item.category[term] <rss><channel><item><category>
     *
     * @return string
     */
    public function getName();
}
