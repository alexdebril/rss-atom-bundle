<?php

/**
 * Rss/Atom Bundle for Symfony.
 *
 *
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @copyright (c) 2013, Alexandre Debril
 */
namespace Debril\RssAtomBundle\Protocol\Parser;

use Debril\RssAtomBundle\Protocol\CategoryInInterface;
use Debril\RssAtomBundle\Protocol\CategoryOutInterface;

/**
 * Class Category
 * @deprecated removed in version 3.0
 */
class Category implements CategoryInInterface, CategoryOutInterface
{
    /**
     * Atom : feed.entry.category <feed><entry><category>
     * Rss  : rss.channel.item.category[term] <rss><channel><item><category>
     *
     * @var string
     */
    protected $name;

    /**
     * @return string
     * @deprecated removed in version 3.0
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     * @deprecated removed in version 3.0
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}
