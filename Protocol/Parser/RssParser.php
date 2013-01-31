<?php
/**
 * Rss/Atom Bundle for Symfony 2
 *
 * @package RssBundle\Protocol
 *
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @copyright (c) 2013, Alexandre Debril
 *
 */
namespace Debril\RssBundle\Protocol\Parser;

use Debril\RssBundle\Protocol\Parser;
use Debril\RssBundle\Protocol\FeedContent;
use Debril\RssBundle\Protocol\Item;

use \SimpleXMLElement;

class RssParser extends Parser
{
    protected $mandatoryFields = array(
            'channel',
        );

    /**
     * @param SimpleXMLElement $xmlBody
     * @return boolean
     */
    public function canHandle(SimpleXMLElement $xmlBody)
    {
        return isset($xmlBody->channel);
    }

    /**
     * @param SimpleXMLElement $xmlBody
     * @return \Debril\RssBundle\Protocol\FeedContent
     */
    protected function parseBody( SimpleXMLElement $xmlBody )
    {
        $feedContent = new FeedContent();

        $feedContent->setId($xmlBody->channel->link);
        $feedContent->setLink($xmlBody->channel->link);
        $feedContent->setTitle($xmlBody->channel->title);

        $updated = self::convertToDateTime($xmlBody->channel->lastBuildDate);
        $feedContent->setLastModified($updated);

        foreach( $xmlBody->channel->item as $domElement )
        {
            $item = new Item();
            $item->setTitle($domElement->title)
                ->setSummary($domElement->description)
                ->setId($domElement->guid)
                ->setUpdated(self::convertToDateTime($domElement->pubDate))
                ->setLink($domElement->link);

            $feedContent->addItem($item);
        }

        return $feedContent;
    }

}