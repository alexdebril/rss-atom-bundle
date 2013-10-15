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
     * @param Debril\RssAtomBundle\Protocol\FeedIn $feed
     * @param \DateTime $modifiedSince
     * @return \\Debril\RssAtomBundle\Protocol\FeedIn
     */
    protected function parseBody(SimpleXMLElement $xmlBody, FeedIn $feed, \DateTime $modifiedSince)
    {
        $feed->setPublicId($xmlBody->id);

        $feed->setLink(current($this->detectLink($xmlBody, 'self')));
        $feed->setTitle($xmlBody->title);
        $feed->setDescription($xmlBody->subtitle);

        $format = $this->guessDateFormat($xmlBody->updated);
        $updated = self::convertToDateTime($xmlBody->updated, $format);
        $feed->setLastModified($updated);

        foreach ($xmlBody->entry as $xmlElement)
        {
            $itemFormat = isset($itemFormat) ? $itemFormat : $this->guessDateFormat($xmlElement->updated);

            $item = $this->newItem();
            $item->setTitle($xmlElement->title)
                    ->setPublicId($xmlElement->id)
                    ->setSummary($xmlElement->summary)
                    ->setDescription($this->parseContent($xmlElement->content))
                    ->setUpdated(self::convertToDateTime($xmlElement->updated, $itemFormat));

            $item->setLink($this->detectLink($xmlElement, 'alternate'));

            if ($xmlElement->author)
            {
                $item->setAuthor($xmlElement->author->name);
            }

            $this->addAcceptableItem($feed, $item, $modifiedSince);
        }

        return $feed;
    }

    /**
     *
     * @param SimpleXMLElement $element
     * @param type $type
     */
    protected function detectLink(SimpleXMLElement $xmlElement, $type)
    {
        foreach ($xmlElement->link as $xmlLink)
        {
            if ((string) $xmlLink['rel'] === $type)
            {
                return $xmlLink['href'];
            }
        }

        // return the first if the desired link does not exist
        return $xmlElement->link[0]['href'];
    }

    protected function parseContent(SimpleXMLElement $content)
    {
        if (0 < $content->children()->count())
        {
            $out = '';
            foreach ($content->children() as $child)
            {
                $out .= $child->asXML();
            }
            return $out;
        }

        return $content;
    }

}

