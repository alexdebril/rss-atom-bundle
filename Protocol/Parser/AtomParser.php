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

class AtomParser extends Parser
{

    protected $mandatoryFields = array(
            'id',
            'updated',
            'title',
            'subtitle',
            'link',
            'entry',
        );

    /**
     * @param SimpleXMLElement $xmlBody
     * @return boolean
     */
    public function canHandle(SimpleXMLElement $xmlBody)
    {
        return 'feed' === $xmlBody->getName();
     }


    /**
     * @param SimpleXMLElement $xmlBody
     * @return \Debril\RssBundle\Protocol\FeedContent
     */
    protected function parseBody( SimpleXMLElement $xmlBody )
    {
        $feedContent = new FeedContent();

        $feedContent->setId($xmlBody->id);

        $feedContent->setLink(current($xmlBody->link[0]['href']));
        $feedContent->setTitle($xmlBody->title);
        $feedContent->setSubtitle($xmlBody->subtitle);

        $updated = self::convertToDateTime($xmlBody->updated, \DateTime::RFC3339);
        $feedContent->setLastModified($updated);

        foreach( $xmlBody->entry as $domElement )
        {
            $item = new Item();
            $item->setTitle($domElement->title)
                ->setId($domElement->id)
                ->setSummary($domElement->summary)
                ->setUpdated(self::convertToDateTime($domElement->updated, \DateTime::RFC3339))
                ->setLink($domElement->link);

            $feedContent->addItem($item);
        }

        return $feedContent;
    }

}