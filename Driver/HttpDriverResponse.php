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

class HttpDriverResponse
{
    /**
     *
     * @var string
     */
    protected $body;

    /**
     *
     * @var array
     */
    protected $headers;

    /**
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     *
     * @param array $headers
     * @return \Debril\RssBundle\Driver\HttpDriverResponse
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getBody() {
        return $this->body;
    }

    /**
     *
     * @param string $body
     * @return \Debril\RssBundle\HttpDriverResponse
     */
    public function setBody($body) {
        $this->body = $body;

        return $this;
    }

}
