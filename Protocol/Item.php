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

interface Item
{

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @return string
     */
    public function getId();

    /**
     *
     * @return string
     */
    public function getDescription();

    /**
     * @return string
     */
    public function getSummary();

    /**
     * @param unknown_type $summary
     * @return \Debril\RssAtomBundle\Protocol\Item
     */
    public function setSummary($summary);

    /**
     * @return DateTime
     */
    public function getUpdated();

    /**
     * @return string
     */
    public function getLink();

    /**
     *
     * @return \Debril\RssAtomBundle\Protocol\Author
     */
    public function getAuthor();

    /**
     *
     * @return string
     */
    public function getComment();
}
