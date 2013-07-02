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
use Debril\RssAtomBundle\Protocol\Parser\FeedContent;
use Debril\RssAtomBundle\Protocol\Parser\Item;
use \SimpleXMLElement;

class RdfParser extends Parser
{

    protected $mandatoryFields = array(
        'channel',
    );

    /**
     *
     */
    public function __construct()
    {
        $this->setdateFormats(array(\DateTime::W3C, 'Y-m-d'));
    }

    /**
     * @param SimpleXMLElement $xmlBody
     * @return boolean
     */
    public function canHandle(SimpleXMLElement $xmlBody)
    {
        return 'rdf' === strtolower($xmlBody->getName());
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
        $feedContent->setDescription($xmlBody->channel->description);

        if (isset($xmlBody->channel->date))
        {
            $date = $xmlBody->channel->children('dc', true);
            $updated = self::convertToDateTime($date[0], $this->guessDateFormat($date[0]));
            $feedContent->setLastModified($updated);
        }

        foreach ($xmlBody->item as $xmlElement)
        {
            $item = new Item();
            $date = $xmlElement->children('dc', true);
            $format = isset($format) ? $format : $this->guessDateFormat($date[0]);

            $item->setTitle($xmlElement->title)
                    ->setDescription($xmlElement->description)
                    ->setUpdated(self::convertToDateTime($date[0], $format))
                    ->setLink($xmlElement->link);

            $feedContent->addAcceptableItem($item, $modifiedSince);
        }

        return $feedContent;
    }

}

