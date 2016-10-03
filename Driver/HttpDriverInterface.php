<?php

/**
 * Rss/Atom Bundle for Symfony.
 *
 *
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @copyright (c) 2013, Alexandre Debril
 */
namespace Debril\RssAtomBundle\Driver;

/**
 * Interface HttpDriverInterface.
 */
interface HttpDriverInterface
{
    /**
     * @param string    $url
     * @param \DateTime $lastModified
     *
     * @return HttpDriverResponse
     * @deprecated removed in version 3.0
     */
    public function getResponse($url, \DateTime $lastModified);
}
