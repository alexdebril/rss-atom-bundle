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
     *
     * @param \Debril\RssAtomBundle\Protocol\ItemIn $item
     */
    public function addItem(ItemIn $item);

    /**
     *
     * @return array[\Debril\RssAtomBundle\Protocol\ItemIn]
     */
    public function getItems();

    /**
     *
     * @param \DateTime $lastModified
     */
    public function setLastModified(\DateTime $lastModified);

    /**
     *
     * @param string $title
     */
    public function setTitle($title);

    /**
     *
     * @param string $description
     */
    public function setDescription($description);

    /**
     *
     * @param string $link
     */
    public function setLink($link);

    /**
     * @deprecated
     * @param mixed $id
     */
    public function setId($id);

    /**
     *
     * @param string $id
     */
    public function setPublicId($id);
}

