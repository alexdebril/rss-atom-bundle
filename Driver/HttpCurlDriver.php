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

class HttpCurlDriver implements HttpDriver
{

    /**
     *
     * @param type $url
     * @param \DateTime $lastModified
     * @return \Debril\RssAtomBundle\Driver\HttpDriverResponse
     * @throws DriverUnreachableResourceException
     */
    public function getResponse($url, \DateTime $lastModified)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_TIMECONDITION, CURL_TIMECOND_IFMODSINCE);
        curl_setopt($curl, CURLOPT_TIMEVALUE, $lastModified->getTimestamp());
        $curlReturn = curl_exec($curl);

        if ( ! $curlReturn )
        {
            $err = curl_error($curl);
            throw new DriverUnreachableResourceException("Error accessing {$url} : {$err}");
        }
        $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $headerstring = substr($curlReturn, 0, $headerSize);
        $body = substr($curlReturn, $headerSize);

        $headers = explode("\n", $headerstring);

        $response = new HttpDriverResponse();

        $response->setBody($body);
        $response->setHeaders($headers);

        return $response;
    }
}