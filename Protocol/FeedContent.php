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

interface FeedContent
{

    /**
     * @return \DateTime
     */
    public function getLastModified();

    /**
     *
     * @return string
     */
    public function getTitle();

    /**
     * atom : subtitle
     * @return string
     */
    public function getDescription();

    /**
     *
     * @return string
     */
    public function getLink();

    /**
     *
     * @return string
     */
    public function getId();

    /**
     *
     * @return array[\Debril\RssAtomBundle\Protocol\Item]
     */
    public function getItems();
}
