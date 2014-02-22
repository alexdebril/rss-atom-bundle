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
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:27.0) Gecko/20100101 Firefox/27.0');
        $curlReturn = curl_exec($curl);

        if (!$curlReturn)
        {
            $err = curl_error($curl);
            throw new DriverUnreachableResourceException("Error accessing {$url} : {$err}");
        }

        $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        curl_close($curl);

        return $this->getHttpResponse(
                        substr($curlReturn, 0, $headerSize), substr($curlReturn, $headerSize)
        );
    }

    /**
     *
     * @param string $headerString
     * @param string $body
     * @return \Debril\RssAtomBundle\Driver\HttpDriverResponse
     */
    public function getHttpResponse($headerString, $body)
    {
        $headers = array();
        preg_match('/(?<version>\S+) (?P<code>\d+) (?P<message>\V+)/', $headerString, $headers);

        $response = new HttpDriverResponse();

        $response->setBody($body);
        $response->setHttpCode($headers['code']);
        $response->setHttpMessage($headers['message']);
        $response->setHttpVersion($headers['version']);
        $response->setHeaders($headerString);

        return $response;
    }

}
