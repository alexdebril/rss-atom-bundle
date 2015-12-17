<?php

namespace Debril\RssAtomBundle\Driver;

use GuzzleHttp\Client;

/**
 * Class GuzzleBridgeDriver
 */
class GuzzleBridgeDriver implements HttpDriverInterface
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string    $url
     * @param \DateTime $lastModified
     *
     * @return HttpDriverResponse
     */
    public function getResponse($url, \DateTime $lastModified)
    {
        $ressource = $this->client->request('GET', $url);

        $response = new HttpDriverResponse();
        $response->setHttpCode($ressource->getStatusCode());
        $response->setHttpVersion($ressource->getProtocolVersion());
        $response->setHttpMessage($ressource->getReasonPhrase());
        $response->setHeaders($ressource->getHeaders());
        $response->setBody($ressource->getBody());

        return $response;
    }
}
