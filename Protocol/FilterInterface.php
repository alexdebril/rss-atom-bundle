<?php

/**
 * Rss/Atom Bundle for Symfony.
 *
 *
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @copyright (c) 2013, Alexandre Debril
 */
namespace Debril\RssAtomBundle\Protocol;

use Debril\RssAtomBundle\Protocol\Parser\Item;
use Debril\RssAtomBundle\Protocol\ItemOutInterface;

/**
 * Interface FilterInterface.
 */
interface FilterInterface
{
    /**
     * @param Item $item
     *
     * @return bool
     * @deprecated removed in version 3.0
     */
    public function isValid(ItemOutInterface $item);
}
