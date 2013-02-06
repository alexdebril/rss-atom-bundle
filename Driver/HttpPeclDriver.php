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
use \HttpRequest;
use Debril\RssAtomBundle\Driver\HttpDriver;
use Debril\RssAtomBundle\Driver\HttpDriverResponse;

class HttpPeclDriver implements HttpDriver
{
    /**
     *
     * @param type $url
     * @param \DateTime $lastModified
     * @return \Debril\RssAtomBundle\Driver\HttpDriverResponse
     */
    public function getResponse($url, \DateTime $lastModified)
    {
        $request = new HttpRequest($url);

        $request->setOptions(
                        array(
                                'If-Modified-Since' => $lastModified->format(DateTime::RFC822)
                             )
                );
        $response = $request->send();

        $httpResponse = new HttpDriverResponse();
        $httpResponse->setBody( $response->getBody() );
        $httpResponse->setHeaders($response->getHeaders());

        return $httpResponse;
    }
}