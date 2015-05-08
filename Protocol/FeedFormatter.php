<?php

/**
 * Rss/Atom Bundle for Symfony 2.
 *
 *
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @copyright (c) 2013, Alexandre Debril
 */
namespace Debril\RssAtomBundle\Protocol;

/**
 * Class FeedFormatter.
 */
abstract class FeedFormatter
{
    /**
     * @return \DomDocument
     */
    abstract public function getRootElement();

    /**
     * @param \DomDocument                           $element
     * @param \Debril\RssAtomBundle\Protocol\FeedOutInterface $content
     */
    abstract public function setMetas(\DomDocument $element, FeedOutInterface $content);

    /**
     * @param \DomDocument                           $document
     * @param \Debril\RssAtomBundle\Protocol\ItemOutInterface $item
     */
    abstract protected function addEntry(\DomDocument $document, ItemOutInterface $item);

    /**
     * @param \Debril\RssAtomBundle\Protocol\FeedOutInterface $content
     */
    public function toString(FeedOutInterface $content)
    {
        $element = $this->toDom($content);

        return $element->saveXML();
    }

    /**
     * @param \Debril\RssAtomBundle\Protocol\FeedOutInterface $content
     */
    public function toDom(FeedOutInterface $content)
    {
        $element = $this->getRootElement();

        $this->setMetas($element, $content);
        $this->setEntries($element, $content);

        return $element;
    }

    /**
     * @param \DomDocument                           $document
     * @param \Debril\RssAtomBundle\Protocol\FeedOutInterface $content
     */
    public function setEntries(\DomDocument $document, FeedOutInterface $content)
    {
        $items = $content->getItems();
        foreach ($items as $item) {
            $this->addEntry($document, $item);
        }
    }
}
