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

/**
 * Interface used to send a RSS/ATOM stream to Formatter classes
 */
interface FeedOut
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
     * @return array[\Debril\RssAtomBundle\Protocol\ItemOut]
     */
    public function getItems();
}
