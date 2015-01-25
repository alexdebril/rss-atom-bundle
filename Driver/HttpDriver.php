<?php
/**
 * Rss/Atom Bundle for Symfony 2
 *
 * @package RssAtomBundle\Driver
 *
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @copyright (c) 2013, Alexandre Debril
 *
 */
namespace Debril\RssAtomBundle\Driver;
use \DateTime;

/**
 * Interface HttpDriver
 * @deprecated will be removed in version 2.0
 * @package Debril\RssAtomBundle\Driver
 */
interface HttpDriver
{

    /**
     *
     * @param string   $url
     * @param DateTime $lastModified
     *
     * @return HttpDriverResponse
     */
    public function getResponse($url, DateTime $lastModified);

}
