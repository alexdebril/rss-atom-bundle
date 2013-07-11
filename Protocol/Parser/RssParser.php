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

use Debril\RssAtomBundle\Protocol\Parser;
use Debril\RssAtomBundle\Protocol\FeedIn;
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
     * @param \Debril\RssAtomBundle\Protocol\FeedIn $feed
     * @param \DateTime $modifiedSince
     * @return \Debril\RssAtomBundle\Protocol\FeedIn
     */
    protected function parseBody(SimpleXMLElement $xmlBody, FeedIn $feed, \DateTime $modifiedSince)
    {
        $feed->setId($xmlBody->channel->link);
        $feed->setLink($xmlBody->channel->link);
        $feed->setTitle($xmlBody->channel->title);
        $feed->setDescription($xmlBody->channel->description);

        if (isset($xmlBody->channel->lastBuildDate))
        {
            $format = $this->guessDateFormat($xmlBody->channel->lastBuildDate);
            $updated = self::convertToDateTime($xmlBody->channel->lastBuildDate, $format);
            $feed->setLastModified($updated);
        }

        foreach ($xmlBody->channel->item as $xmlElement)
        {
            $item = $this->newItem();
            $format = isset($format) ? $format : $this->guessDateFormat($xmlElement->pubDate);
            $item->setTitle($xmlElement->title)
                    ->setDescription($xmlElement->description)
                    ->setId($xmlElement->guid)
                    ->setUpdated(self::convertToDateTime($xmlElement->pubDate, $format))
                    ->setLink($xmlElement->link)
                    ->setComment($xmlElement->comments)
                    ->setAuthor($xmlElement->author);

            $this->addAcceptableItem($feed, $item, $modifiedSince);
        }

        return $feed;
    }

}

