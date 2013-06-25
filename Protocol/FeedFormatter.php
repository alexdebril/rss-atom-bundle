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

abstract class FeedFormatter
{

    /**
     * @return \DomDocument
     */
    abstract public function getRootElement();

    /**
     *
     * @param \DomDocument $element
     * @param \Debril\RssAtomBundle\Protocol\FeedContent $content
     */
    abstract public function setMetas(\DomDocument $element, FeedContent $content);

    /**
     * @param \DomDocument $element
     * @param \Debril\RssAtomBundle\Protocol\Item $item
     */
    abstract protected function addEntry(\DomDocument $document, Item $item);

    /**
     *
     * @param \Debril\RssAtomBundle\Protocol\FeedContent $content
     */
    public function toString(FeedContent $content)
    {
        $element = $this->toDom($content);

        return $element->saveXML();
    }

    /**
     *
     * @param \Debril\RssAtomBundle\Protocol\FeedContent $content
     */
    public function toDom(FeedContent $content)
    {
        $element = $this->getRootElement();

        $this->setMetas($element, $content);
        $this->setEntries($element, $content);

        return $element;
    }

    /**
     *
     * @param \DomDocument $element
     * @param \Debril\RssAtomBundle\Protocol\FeedContent $content
     */
    public function setEntries(\DomDocument $document, FeedContent $content)
    {
        $items = $content->getItems();
        foreach ($items as $item)
        {
            $this->addEntry($document, $item);
        }
    }

}
