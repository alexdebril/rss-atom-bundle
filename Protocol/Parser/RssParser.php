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
use Debril\RssAtomBundle\Protocol\Parser\Item;
use Debril\RssAtomBundle\Protocol\Author;
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

        foreach ($xmlBody->channel->item as $xmlElement)
        {
            $item = new Item();
            $format = isset($format) ? $format : $this->guessDateFormat($xmlElement->pubDate);
            $item->setTitle($xmlElement->title)
                    ->setSummary($xmlElement->description)
                    ->setId($xmlElement->guid)
                    ->setUpdated(self::convertToDateTime($xmlElement->pubDate, $format))
                    ->setLink($xmlElement->link)
                    ->setComment($xmlElement->comments);

            if ($xmlElement->author)
            {
                $author = new Author;
                $author->setName($xmlElement->author);

                $item->setAuthor($author);
            }
            $feedContent->addAcceptableItem($item, $modifiedSince);
        }

        return $feedContent;
    }

}

