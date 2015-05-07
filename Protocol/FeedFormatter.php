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
 * Class FeedFormatter
 * @package Debril\RssAtomBundle\Protocol
 */
abstract class FeedFormatter
{

    /**
     * @return \DomDocument
     */
    abstract public function getRootElement();

    /**
     *
     * @param \DomDocument                           $element
     * @param \Debril\RssAtomBundle\Protocol\FeedOut $content
     */
    abstract public function setMetas(\DomDocument $element, FeedOut $content);

    /**
     * @param \DomDocument                           $document
     * @param \Debril\RssAtomBundle\Protocol\ItemOut $item
     */
    abstract protected function addEntry(\DomDocument $document, ItemOut $item);

    /**
     *
     * @param \Debril\RssAtomBundle\Protocol\FeedOut $content
     */
    public function toString(FeedOut $content)
    {
        $element = $this->toDom($content);

        return $element->saveXML();
    }

    /**
     *
     * @param \Debril\RssAtomBundle\Protocol\FeedOut $content
     */
    public function toDom(FeedOut $content)
    {
        $element = $this->getRootElement();

        $this->setMetas($element, $content);
        $this->setEntries($element, $content);

        return $element;
    }

    /**
     * @param \DomDocument                           $document
     * @param \Debril\RssAtomBundle\Protocol\FeedOut $content
     */
    public function setEntries(\DomDocument $document, FeedOut $content)
    {
        $items = $content->getItems();
        foreach ($items as $item) {
            $this->addEntry($document, $item);
        }
    }

}
