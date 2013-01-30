<?php
/**
 * Rss/Atom Bundle for Symfony 2
 *
 * @package RssBundle\Driver
 *
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @copyright (c) 2013, Alexandre Debril
 * 
 */
namespace Debril\RssBundle\Driver;
use \DateTime;

interface HttpDriver
{

    /**
     *
     * @param string $url
     * @param DateTime $lastModified
     *
     * @return HttpDriverResponse
     */
    public function getResponse($url, DateTime $lastModified);

}