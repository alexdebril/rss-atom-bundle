<?php

/**
 * Rss/Atom Bundle for Symfony.
 *
 *
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @copyright (c) 2013, Alexandre Debril
 */
namespace Debril\RssAtomBundle\Protocol\Parser;

use Debril\RssAtomBundle\Protocol\FeedInterface;
use Debril\RssAtomBundle\Protocol\ItemInInterface;
use Debril\RssAtomBundle\Protocol\Parser;
use SimpleXMLElement;

/**
 * Class RssParser.
 */
class RssParser extends Parser
{
    protected $mandatoryFields = array(
        'channel',
    );

    /**
     *
     */
    public function __construct()
    {
        $this->setdateFormats(array(\DateTime::RSS));
    }

    /**
     * @param SimpleXMLElement $xmlBody
     *
     * @return bool
     */
    public function canHandle(SimpleXMLElement $xmlBody)
    {
        return 'rss' === strtolower($xmlBody->getName());
    }

    /**
     * @param SimpleXMLElement $xmlBody
     * @param FeedInterface    $feed
     * @param array            $filters
     *
     * @return FeedInterface
     */
    protected function parseBody(SimpleXMLElement $xmlBody, FeedInterface $feed, array $filters)
    {
        $namespaces = $xmlBody->getNamespaces(true);

        $feed->setPublicId($xmlBody->channel->link);
        $feed->setLink($xmlBody->channel->link);
        $feed->setTitle($xmlBody->channel->title);
        $feed->setDescription($xmlBody->channel->description);

        $latest = new \DateTime('@0');
        $date = new \DateTime('now');
        foreach ($xmlBody->channel->item as $xmlElement) {
            $item = $this->newItem();

            if (isset($xmlElement->pubDate)) {
                $readDate = trim($xmlElement->pubDate);

                $format = isset($format) ? $format : $this->guessDateFormat($readDate);
                $date = static::convertToDateTime($readDate, $format);
            }

            $item->setTitle($xmlElement->title)
                 ->setDescription($xmlElement->description)
                 ->setPublicId($xmlElement->guid)
                 ->setUpdated($date)
                 ->setLink($xmlElement->link)
                 ->setComment($xmlElement->comments);

            if ($date > $latest) {
                $latest = $date;
            }

            $this->parseCategories($xmlElement, $item);

            $this->handleAuthor($xmlElement, $item);
            $this->handleDescription($xmlElement, $item);

            $item->setAdditional($this->getAdditionalNamespacesElements($xmlElement, $namespaces));

            $this->handleEnclosure($xmlElement, $item);
            $this->handleMediaExtension($xmlElement, $item);

            $this->addValidItem($feed, $item, $filters);
        }

        $this->detectAndSetLastModified($xmlBody, $feed, $latest);

        return $feed;
    }

    /**
     * @param SimpleXMLElement $xmlBody
     * @param FeedInterface    $feed
     * @param $latestItemDate
     */
    protected function detectAndSetLastModified(SimpleXMLElement $xmlBody, FeedInterface $feed, $latestItemDate)
    {
        if (isset($xmlBody->channel->lastBuildDate)) {
            $this->setLastModified($feed, $xmlBody->channel->lastBuildDate);
        } elseif (isset($xmlBody->channel->pubDate)) {
            $this->setLastModified($feed, $xmlBody->channel->pubDate);
        } else {
            $feed->setLastModified($latestItemDate);
        }
    }

    /**
     * @param FeedInterface $feed
     * @param string        $rssDate
     */
    protected function setLastModified(FeedInterface $feed, $rssDate)
    {
        $format = $this->guessDateFormat($rssDate);
        $updated = static::convertToDateTime($rssDate, $format);
        $feed->setLastModified($updated);
    }

    /**
     * Handles enclosures if any.
     *
     * @param SimpleXMLElement $element
     * @param ItemInInterface  $item
     *
     * @return $this
     */
    protected function handleEnclosure(SimpleXMLElement $element, ItemInInterface $item)
    {
        if (isset($element->enclosure)) {
            foreach ($element->enclosure as $enclosure) {
                $media = $this->createMedia($enclosure);
                $item->addMedia($media);
            }
        }

        return $this;
    }

    /**
     * According to RSS specs, either we can have a summary in description ;
     * full content in description ; or a summary in description AND full content in content:encoded
     *
     * @param SimpleXMLElement $xmlElement
     * @param ItemInInterface $item
     */
    protected function handleDescription(SimpleXMLElement $xmlElement, ItemInInterface $item)
    {
        $contentChild = $xmlElement->children('http://purl.org/rss/1.0/modules/content/');

        if (isset($contentChild->encoded)) {
            $item->setDescription($contentChild->encoded);
            $item->setSummary($xmlElement->description);
        } else {
            $item->setDescription($xmlElement->description);
        }
    }

    /**
     * Parse elements from Yahoo RSS Media extension
     *
     * @param SimpleXMLElement $xmlElement
     * @param ItemInInterface $item with Media added
     */
    protected function handleMediaExtension(SimpleXMLElement $xmlElement, ItemInInterface $item)
    {
        foreach ($xmlElement->children('http://search.yahoo.com/mrss/') as $xmlMedia) {
            $media = new Media();
            $media->setUrl($this->getAttributeValue($xmlMedia, 'url'))
                ->setType($this->searchAttributeValue($xmlMedia, array('type', 'medium')))
                ->setLength($this->getAttributeValue($xmlMedia, 'fileSize'))
            ;

            $item->addMedia($media);
        }
    }

    /**
     * Parse category elements.
     * We may have more than one.
     *
     * @param SimpleXMLElement $element
     * @param ItemInInterface $item
     */
    protected function parseCategories(SimpleXMLElement $element, ItemInInterface $item)
    {
        foreach ($element->category as $xmlCategory) {
            $category = new Category();
            $category->setName((string) $xmlCategory);

            $item->addCategory($category);
        }
    }

    /**
     * Parse author:
     * first we look at optional dc:creator, which is the author name
     * if no, we fallback to the RSS author element which is the author email
     *
     * @param SimpleXMLElement $element
     * @param ItemInInterface $item
     */
    protected function handleAuthor(SimpleXMLElement $element, ItemInInterface $item)
    {
        $dcChild = $element->children('http://purl.org/dc/elements/1.1/');

        if (isset($dcChild->creator)) {
            $item->setAuthor((string) $dcChild->creator);
        } else {
            $item->setAuthor((string) $element->author);
        }
    }
}
