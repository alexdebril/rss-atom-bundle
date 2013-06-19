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

use Debril\RssAtomBundle\Protocol\FeedContent;

interface FeedFormatter
{

    /**
     *
     * @param \Debril\RssAtomBundle\Protocol\FeedContent $content
     */
    public function toString(FeedContent $content);

    /**
     *
     * @param \Debril\RssAtomBundle\Protocol\FeedContent $content
     */
    public function toDom(FeedContent $content);

    /**
     * @return \DomDocument
     */
    public function getRootElement();

    /**
     *
     * @param \DomDocument $element
     * @param \Debril\RssAtomBundle\Protocol\FeedContent $content
     */
    public function setMetas(\DomDocument $element, FeedContent $content);

    /**
     *
     * @param \DomDocument $element
     * @param \Debril\RssAtomBundle\Protocol\FeedContent $content
     */
    public function setEntries(\DomDocument $element, FeedContent $content);
}
