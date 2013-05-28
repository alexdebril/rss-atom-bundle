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

class AtomParser extends Parser
{

    protected $mandatoryFields = array(
        'id',
        'updated',
        'title',
        'link',
        'entry',
    );

    /**
     * 
     */
    public function __construct()
    {
        $this->setdateFormats(array(\DateTime::RFC3339));
    }

    /**
     * @param SimpleXMLElement $xmlBody
     * @return boolean
     */
    public function canHandle(SimpleXMLElement $xmlBody)
    {
        return 'feed' === $xmlBody->getName();
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

        $feedContent->setId($xmlBody->id);

        $feedContent->setLink(current($xmlBody->link[0]['href']));
        $feedContent->setTitle($xmlBody->title);
        $feedContent->setSubtitle($xmlBody->subtitle);

        $format = $this->guessDateFormat($xmlBody->updated);
        $updated = self::convertToDateTime($xmlBody->updated, $format);
        $feedContent->setLastModified($updated);

        foreach ($xmlBody->entry as $domElement)
        {
            $item = new Item();
            $item->setTitle($domElement->title)
                    ->setId($domElement->id)
                    ->setSummary($domElement->summary)
                    ->setUpdated(self::convertToDateTime($domElement->updated, $format))
                    ->setLink($domElement->link);

            $feedContent->addAcceptableItem($item, $modifiedSince);
        }

        return $feedContent;
    }

}

