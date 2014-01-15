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
        $feed->setPublicId($xmlBody->channel->link);
        $feed->setLink($xmlBody->channel->link);
        $feed->setTitle($xmlBody->channel->title);
        $feed->setDescription($xmlBody->channel->description);

        // @todo make that clean ...
        $mustPickLatest = false;
        $latest = clone $modifiedSince;
        if (isset($xmlBody->channel->lastBuildDate))
        {
            $this->setLastModified($feed, $xmlBody->channel->lastBuildDate);
        } elseif (isset($xmlBody->channel->pubDate))
        {
            $this->setLastModified($feed, $xmlBody->channel->pubDate);
        } else
        {
            $mustPickLatest = true;
        }

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

            if ($mustPickLatest && $date > $latest)
            {
                $latest = $date;
                $feed->setLastModified($date);
            }

            $this->addAcceptableItem($feed, $item, $modifiedSince);
        }

        return $feed;
    }

    /**
     *
     * @param \Debril\RssAtomBundle\Protocol\FeedIn $feed
     * @param type $rssDate
     */
    protected function setLastModified(FeedIn $feed, $rssDate)
    {
        $format = $this->guessDateFormat($rssDate);
        $updated = self::convertToDateTime($rssDate, $format);
        $feed->setLastModified($updated);
    }

}
