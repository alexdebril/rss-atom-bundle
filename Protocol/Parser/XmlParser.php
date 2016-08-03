<?php

/**
 * Rss/Atom Bundle for Symfony.
 *
 *
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @copyright (c) 2013, Alexandre Debril
 */
namespace Debril\RssAtomBundle\Protocol\Parser;

/**
 * Class XmlParser
 *
 */
class XmlParser
{
    /**
     * Parse a string and return an XML element
     * Pretty much a no-op there, but you may want top override it ti add more
     *
     * @param string $xmlString
     * @return \SimpleXMLElement
     */
    public function parseString($xmlString)
    {
        return new \SimpleXMLElement($xmlString);
    }
}
