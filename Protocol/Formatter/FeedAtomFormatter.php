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
namespace Debril\RssAtomBundle\Protocol\Formatter;

use Debril\RssAtomBundle\Protocol\FeedFormatter;
use Debril\RssAtomBundle\Protocol\FeedContent;

class FeedAtomFormatter implements FeedFormatter
{

    /**
     *
     * @param \Debril\RssAtomBundle\Protocol\FeedContent $content
     * @return string
     */
    public function toString(FeedContent $content)
    {
        $element = $this->toSimpleXml($content);

        return $element->asXML();
    }

    /**
     *
     * @param \Debril\RssAtomBundle\Protocol\FeedContent $content
     * @return \SimpleXMLElement
     */
    public function toSimpleXml(FeedContent $content)
    {
        $element = $this->getRootElement();

        $this->setMetas($element, $content);
        $this->setEntries($element, $content);

        return $element;
    }

    /**
     *
     * @return \SimpleXMLElement
     */
    public function getRootElement()
    {
        $element = new \SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><feed />');
        $element->addAttribute('xmlns', 'http://www.w3.org/2005/Atom');

        return $element;
    }

    /**
     *
     * @param \SimpleXMLElement $element
     * @param \Debril\RssAtomBundle\Protocol\FeedContent $content
     */
    public function setMetas(\SimpleXMLElement $element, FeedContent $content)
    {
        $element->addChild('title', $content->getTitle());
        $element->addChild('subtitle', $content->getSubtitle());
        $element->addChild('id', $content->getId());
        $element->addChild('link', $content->getLink());
        $element->addChild('updated', $content->getLastModified()->format(\DateTime::ATOM));
    }

    /**
     *
     * @param \SimpleXMLElement $element
     * @param \Debril\RssAtomBundle\Protocol\FeedContent $content
     */
    public function setEntries(\SimpleXMLElement $element, FeedContent $content)
    {
        foreach( $content as $item )
        {
            $entry = $element->addChild('entry');
            $entry->addChild('title', $item->getTitle());
            $entry->addChild('link', $item->getLink());
            $entry->addChild('id', $item->getId());
            $entry->addChild('updated', $item->getUpdated()->format(\DateTime::ATOM));
            $entry->addChild('summary', $item->getSummary());
        }
    }
}