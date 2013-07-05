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

namespace Debril\RssAtomBundle\Protocol\Parser;

use Debril\RssAtomBundle\Protocol\Parser\ParsedItem;

interface ParsedFeed
{

    /**
     *
     * @param \Debril\RssAtomBundle\Protocol\Parser\ParsedItem $item
     */
    public function addItem(ParsedItem $item);

    /**
     *
     * @return array[\Debril\RssAtomBundle\Protocol\Parser\ParsedItem]
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
     *
     * @param mixed $id
     */
    public function setId($id);
}

