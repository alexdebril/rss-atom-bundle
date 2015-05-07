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

/**
 * Class FileDriver
 * @package Debril\RssAtomBundle\Driver
 */
class FileDriver implements HttpDriver
{

    /**
     *
     * @param  string       $url
     * @param  \DateTime    $lastModified
     * @return \Debril\RssAtomBundle\Driver\HttpDriverResponse
     * @throws DriverUnreachableResourceException
     */
    public function getResponse($url, \DateTime $lastModified)
    {
        if ( !is_readable($url) ) {
            throw new DriverUnreachableResourceException("not found or not readable : {$url}");
        }

        $body = file_get_contents($url);

        $response = new HttpDriverResponse();
        $response->setHttpCode(HttpDriverResponse::HTTP_CODE_OK);
        $response->setBody($body);

        return $response;
    }
}
