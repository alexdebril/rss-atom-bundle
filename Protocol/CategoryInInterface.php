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
 * Class CategoryInInterface
 *
 * Interface used when reading an external feed.
 */
interface CategoryInInterface
{
    /**
     * Atom : feed.entry.category <feed><entry><category>
     * Rss  : rss.channel.item.category[term] <rss><channel><item><category>
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName($name);
}
