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
     * @return array
     */
    public function getHeaders();

    /**
     *
     * @return string
     */
    public function getTitle();

    /**
     *
     * @return string
     */
    public function getSubtitle();

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
     * @return string
     */
    public function getContentType();
}
