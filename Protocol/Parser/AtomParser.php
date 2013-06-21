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
use Debril\RssAtomBundle\Protocol\Author;
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

        foreach ($xmlBody->entry as $xmlElement)
        {
            $item = new Item();
            $item->setTitle($xmlElement->title)
                    ->setId($xmlElement->id)
                    ->setSummary($this->parseContent($xmlElement->content))
                    ->setUpdated(self::convertToDateTime($xmlElement->updated, $format))
                    ->setLink($xmlElement->link[0]['href']);

            if ($xmlElement->author)
            {
                $author = new Author;
                $author->setEmail($xmlElement->author->email);
                $author->setName($xmlElement->author->name);
                $author->setUri($xmlElement->author->uri);

                $item->setAuthor($author);
            }

            $feedContent->addAcceptableItem($item, $modifiedSince);
        }

        return $feedContent;
    }

    protected function parseContent(SimpleXMLElement $content)
    {
        $out = '';
        foreach ($content->children() as $child)
        {
            $out .= $child->asXML();
        }

        return $out;
    }

}

