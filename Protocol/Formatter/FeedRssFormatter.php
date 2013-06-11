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

class FeedRssFormatter implements FeedFormatter
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
        $element = new \SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><rss />');
        $element->addAttribute('version', '2.0');

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
        $element->addChild('link', $content->getLink());
        $element->addChild('lastBuildDate', $content->getLastModified()->format(\DateTime::RSS));
        $element->addChild('pubDate', $content->getLastModified()->format(\DateTime::RSS));
    }

    /**
     *
     * @param \SimpleXMLElement $element
     * @param \Debril\RssAtomBundle\Protocol\FeedContent $content
     */
    public function setEntries(\SimpleXMLElement $element, FeedContent $content)
    {
        foreach ($content as $item)
        {
            $entry = $element->addChild('item');
            $entry->addChild('title', $item->getTitle());
            $entry->addChild('link', $item->getLink());
            $entry->addChild('guid', $item->getLink());
            $entry->addChild('pubDate', $item->getUpdated()->format(\DateTime::RSS));
            $entry->addChild('description', $item->getSummary());
        }
    }

}

