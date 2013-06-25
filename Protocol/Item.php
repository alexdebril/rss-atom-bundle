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
     * atom : content
     * @return string
     */
    public function getDescription();

    /**
     * @return DateTime
     */
    public function getUpdated();

    /**
     * @return string
     */
    public function getLink();

    /**
     * atom : item.author.name
     *
     * @return string
     */
    public function getAuthor();

    /**
     * atom : <link rel="alternate" />
     * @return string
     */
    public function getComment();
}
