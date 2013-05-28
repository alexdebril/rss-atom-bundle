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
use Debril\RssAtomBundle\Protocol\FeedContent;
use Debril\RssAtomBundle\Protocol\Item;
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
        return isset($xmlBody->channel);
    }

    /**
     *
     * @param SimpleXMLElement $xmlBody
     * @param \DateTime $modifiedSince
     * @return \Debril\RssAtomBundle\Protocol\FeedContent
     */
    protected function parseBody(SimpleXMLElement $xmlBody, \DateTime $modifiedSince)
    {
        $feedContent = new FeedContent();

        $feedContent->setId($xmlBody->channel->link);
        $feedContent->setLink($xmlBody->channel->link);
        $feedContent->setTitle($xmlBody->channel->title);

        if (isset($xmlBody->channel->lastBuildDate))
        {
            $format = $this->guessDateFormat($xmlBody->channel->lastBuildDate);
            $updated = self::convertToDateTime($xmlBody->channel->lastBuildDate, $format);
            $feedContent->setLastModified($updated);
        }

        foreach ($xmlBody->channel->item as $domElement)
        {
            $item = new Item();
            $format = isset($format) ? $format : $this->guessDateFormat($domElement->pubDate);
            $item->setTitle($domElement->title)
                    ->setSummary($domElement->description)
                    ->setId($domElement->guid)
                    ->setUpdated(self::convertToDateTime($domElement->pubDate, $format))
                    ->setLink($domElement->link)
                    ->setImage($domElement->image);

            $feedContent->addAcceptableItem($item, $modifiedSince);
        }

        return $feedContent;
    }

}

