<?php

/**
 * Rss/Atom Bundle for Symfony.
 *
 *
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @copyright (c) 2013, Alexandre Debril
 */
namespace Debril\RssAtomBundle\Driver;

use Debril\RssAtomBundle\Exception\DriverUnreachableResourceException;

/**
 * Class HttpCurlDriver.
 */
class HttpCurlDriver implements HttpDriverInterface
{
    /**
     * Configuration options
     * @var array
     */
    private $options;
    
    /**
     * Constructor for passing config options 
     * @param array $options
     */
    public function __construct($options = array()) {

        $this->options = $options;

        $defaults = array('timeout'   => 10,
                          'useragent' => 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 GTB5',
                          'maxredirs' => 5);        
        
        foreach ( $defaults as $key => $value ) {
            if ( !isset( $this->options[$key]) ) {
                $this->options[$key] = $value;
            }
        }
    }
    
    /**
     * @param string    $url
     * @param \DateTime $lastModified
     *
     * @return \Debril\RssAtomBundle\Driver\HttpDriverResponse
     *
     * @throws DriverUnreachableResourceException
     */
    public function getResponse($url, \DateTime $lastModified)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_TIMECONDITION, CURL_TIMECOND_IFMODSINCE);
        curl_setopt($curl, CURLOPT_USERAGENT, $this->options['useragent']);
        curl_setopt($curl, CURLOPT_TIMEVALUE, $lastModified->getTimestamp());
        curl_setopt($curl, CURLOPT_TIMEOUT, $this->options['timeout']);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_MAXREDIRS, $this->options['maxredirs']);
        $curlReturn = curl_exec($curl);

        if (!$curlReturn) {
            $err = curl_error($curl);
            throw new DriverUnreachableResourceException("Error accessing {$url} : {$err}");
        }

        $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        curl_close($curl);

        return $this->getHttpResponse(
            substr($curlReturn, 0, $headerSize),
            substr($curlReturn, $headerSize)
        );
    }

    /**
     * @param string $headerString
     * @param string $body
     *
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
