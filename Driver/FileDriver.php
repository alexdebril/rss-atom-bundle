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

use Debril\RssBundle\Driver\DriverUnreachableResourceException;

class FileDriver implements HttpDriver
{

    /**
     *
     * @param string $url
     * @param \DateTime $lastModified
     * @return \Debril\RssBundle\Driver\HttpDriverResponse
     * @throws DriverUnreachableResourceException
     */
    public function getResponse($url, \DateTime $lastModified)
    {
        if ( !is_readable($url) )
        {
            throw new DriverUnreachableResourceException("not found or not readable : {$url}");
        }

        $body = file_get_contents($url);

        $response = new HttpDriverResponse();
        $response->setBody($body);

        return $response;
    }
}