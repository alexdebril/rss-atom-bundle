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
 * @deprecated removed in version 3.0
 */
class XmlParser
{
    /**
     * Parse a string and return an XML element
     * Pretty much a no-op there, but you may want top override it ti add more
     *
     * @param string $xmlString
     * @return \SimpleXMLElement
     * @deprecated removed in version 3.0
     */
    public function parseString($xmlString)
    {
        return new \SimpleXMLElement($xmlString);
    }
}
