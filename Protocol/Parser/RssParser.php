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

namespace Debril\RssAtomBundle\Protocol\Parser;

use Debril\RssAtomBundle\Protocol\FeedInterface;
use Debril\RssAtomBundle\Protocol\Parser;
use \SimpleXMLElement;

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
     * @return boolean
     */
    public function canHandle(SimpleXMLElement $xmlBody)
    {
        return 'rss' === strtolower($xmlBody->getName());
    }

    /**
     *
     * @param SimpleXMLElement $xmlBody
     * @param \Debril\RssAtomBundle\Protocol\FeedInterface $feed
     * @param array $filters
     * @return \Debril\RssAtomBundle\Protocol\FeedInterface
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
        foreach ($xmlBody->channel->item as $xmlElement)
        {
            $item = $this->newItem();
            if ( isset($xmlElement->pubDate) )
            {
                $format = isset($format) ? $format : $this->guessDateFormat($xmlElement->pubDate);
                $date = self::convertToDateTime($xmlElement->pubDate, $format);
            }
            $item->setTitle($xmlElement->title)
                 ->setDescription($xmlElement->description)
                 ->setPublicId($xmlElement->guid)
                 ->setUpdated($date)
                 ->setLink($xmlElement->link)
                 ->setComment($xmlElement->comments)
                 ->setAuthor($xmlElement->author);

            if ($date > $latest)
            {
                $latest = $date;
            }

            $item->setAdditional($this->getAdditionalNamespacesElements($xmlElement, $namespaces));

            $this->addValidItem($feed, $item, $filters);
        }

        $this->detectAndSetLastModified($xmlBody, $feed, $latest);

        return $feed;
    }

    /**
     * @param SimpleXMLElement $xmlBody
     * @param FeedInterface $feed
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
     *
     * @param \Debril\RssAtomBundle\Protocol\FeedInterface $feed
     * @param type $rssDate
     */
    protected function setLastModified(FeedInterface $feed, $rssDate)
    {
        $format = $this->guessDateFormat($rssDate);
        $updated = self::convertToDateTime($rssDate, $format);
        $feed->setLastModified($updated);
    }

}