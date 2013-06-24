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
use Debril\RssAtomBundle\Protocol\Item;

class FeedAtomFormatter implements FeedFormatter
{

    /**
     *
     * @param \Debril\RssAtomBundle\Protocol\FeedContent $content
     * @return string
     */
    public function toString(FeedContent $content)
    {
        $element = $this->toDom($content);

        return str_replace('default:', '', $element->saveXML());
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
        $element = $dom->createElement('feed');
        $element->setAttribute('xmlns', 'http://www.w3.org/2005/Atom');
        $dom->appendChild($element);

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
        $elements[] = $document->createElement('subtitle', $content->getDescription());
        $elements[] = $document->createElement('id', $content->getLink());

        $link = $document->createElement('link');
        $link->setAttribute('href', $content->getLink());
        $link->setAttribute('rel', 'self');

        $elements[] = $link;
        $elements[] = $document->createElement('updated', $content->getLastModified()->format(\DateTime::ATOM));

        foreach ($elements as $element)
        {
            $document->documentElement->appendChild($element);
        }
    }

    /**
     *
     * @param \SimpleXMLElement $element
     * @param \Debril\RssAtomBundle\Protocol\FeedContent $content
     */
    public function setEntries(\DomDocument $document, FeedContent $content)
    {
        $items = $content->getItems();
        foreach ($items as $item)
        {
            $this->addEntry($document, $item, $content);
        }
    }

    /**
     *
     * @param \DOMDocument $document
     * @param \Debril\RssAtomBundle\Protocol\Item $item
     * @param \Debril\RssAtomBundle\Protocol\FeedContent $content
     */
    protected function addEntry(\DOMDocument $document, Item $item, FeedContent $content)
    {
        $entry = $document->createElement('entry');

        $elements = array();
        $elements[] = $document->createElement('title', htmlspecialchars($item->getTitle()));

        $link = $document->createElement('link');
        $link->setAttribute('href', $item->getLink());
        $elements[] = $link;

        $elements[] = $document->createElement('id', $item->getLink());
        $elements[] = $document->createElement('updated', $item->getUpdated()->format(\DateTime::ATOM));
        $elements[] = $this->generateFragment(
                $document, 'summary', $content->getContentType(), $item->getSummary()
        );

        $elements[] = $this->generateFragment(
                $document, 'content', $content->getContentType(), $item->getDescription()
        );

        if (!is_null($item->getComment()))
        {
            $comments = $document->createElement('link');
            $comments->setAttribute('href', $item->getComment());
            $comments->setAttribute('rel', 'related');

            $elements[] = $comments;
        }

        if (!is_null($item->getAuthor()))
        {
            $author = $document->createElement('author');
            $author->appendChild($document->createElement('name', $item->getAuthor()));

            $elements[] = $author;
        }

        foreach ($elements as $element)
        {
            $entry->appendChild($element);
        }

        $document->documentElement->appendChild($entry);
    }

    /**
     *
     * @param \DOMDocument $document
     * @param string $tag
     * @param string $type
     * @param string $content
     *
     * @return \DomDocumentFragment
     */
    protected function generateFragment(\DOMDocument $document, $tag, $type, $content)
    {
        $fragment = $document->createDocumentFragment();
        $fragment->appendXML("<{$tag} type=\"{$type}\">
                                    {$content}
                              </{$tag}>"
        );

        return $fragment;
    }

}

