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
        $element = $this->toDom($content);

        return $element->saveXML();
    }

    /**
     *
     * @param \Debril\RssAtomBundle\Protocol\FeedContent $content
     * @return \DomDocument
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
     * @return \DomDocument
     */
    public function getRootElement()
    {
        $dom = new \DOMDocument('1.0', 'utf-8');
        $dom->formatOutput = true;

        $rss = $dom->createElement('rss');
        $rss->setAttribute('version', '2.0');
        $channel = $dom->createElement('channel');
        $rss->appendChild($channel);
        $dom->appendChild($rss);

        return $dom;
    }

    /**
     *
     * @param \SimpleXMLElement $element
     * @param \Debril\RssAtomBundle\Protocol\FeedContent $content
     */
    public function setMetas(\DOMDocument $document, FeedContent $content)
    {
        $elements = array();
        $elements[] = $document->createElement('title', htmlspecialchars($content->getTitle()));
        $elements[] = $document->createElement('description', htmlspecialchars($content->getSubtitle()));
        $elements[] = $document->createElement('link', $content->getLink());

        $elements[] = $document->createElement('lastBuildDate', $content->getLastModified()->format(\DateTime::RSS));
        $elements[] = $document->createElement('pubDate', $content->getLastModified()->format(\DateTime::RSS));

        foreach ($elements as $element)
        {
            $document->documentElement->firstChild->appendChild($element);
        }
    }

    /**
     *
     * @param \SimpleXMLElement $element
     * @param \Debril\RssAtomBundle\Protocol\FeedContent $content
     */
    public function setEntries(\DomDocument $document, FeedContent $content)
    {
        foreach ($content as $item)
        {
            $entry = $document->createElement('item');

            $elements = array();
            $elements[] = $document->createElement('title', htmlspecialchars($item->getTitle()));

            $elements[] = $document->createElement('link', $item->getLink());
            $elements[] = $document->createElement('guid', $item->getLink());
            $elements[] = $document->createElement('pubDate', $item->getUpdated()->format(\DateTime::RSS));
            $elements[] = $document->createElement('comments', $item->getComment());
            $elements[] = $document->createElement('description', $item->getSummary() .
                    $item->getDescription()
            );

            if (!is_null($item->getAuthor()))
            {
                $elements[] = $document->createElement('author', $item->getAuthor()->getName());
            }
            foreach ($elements as $element)
            {
                $entry->appendChild($element);
            }

            $document->documentElement->firstChild->appendChild($entry);
        }
    }

}

