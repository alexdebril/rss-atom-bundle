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
 * Interface Filter.
 */
interface Filter
{
    /**
     * @param \Debril\RssAtomBundle\Protocol\Parser\Item
     *
     * @return bool
     */
    public function isValid(\Debril\RssAtomBundle\Protocol\Parser\Item $item);
}
