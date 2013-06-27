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

class HttpDriverResponse
{

    const HTTP_CODE_OK = 200;
    const HTTP_CODE_NOT_MODIFIED = 304;
    CONST HTTP_CODE_FORBIDDEN = 403;
    const HTTP_CODE_NOT_FOUND = 404;
    const HTTP_CODE_SERVER_ERROR = 500;

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
     * @var int
     */
    protected $httpCode;

    /**
     *
     * @var string
     */
    protected $httpVersion;

    /**
     *
     * @var string
     */
    protected $httpMessage;

    /**
     *
     * @return boolean
     */
    public function getHttpCodeIsOk()
    {
        return $this->getHttpCode() === self::HTTP_CODE_OK;
    }

    /**
     *
     * @return int
     */
    public function getHttpCode()
    {
        return $this->httpCode;
    }

    /**
     *
     * @param int $httpCode
     * @return \Debril\RssAtomBundle\Driver\HttpDriverResponse
     */
    public function setHttpCode($httpCode)
    {
        $this->httpCode = (int) $httpCode;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getHttpVersion()
    {
        return $this->httpVersion;
    }

    /**
     *
     * @param string $httpVersion
     * @return \Debril\RssAtomBundle\Driver\HttpDriverResponse
     */
    public function setHttpVersion($httpVersion)
    {
        $this->httpVersion = $httpVersion;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getHttpMessage()
    {
        return $this->httpMessage;
    }

    /**
     *
     * @param string $httpMessage
     * @return \Debril\RssAtomBundle\Driver\HttpDriverResponse
     */
    public function setHttpMessage($httpMessage)
    {
        $this->httpMessage = $httpMessage;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     *
     * @param string $headers
     * @return \Debril\RssAtomBundle\Driver\HttpDriverResponse
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
    public function getBody()
    {
        return $this->body;
    }

    /**
     *
     * @param string $body
     * @return \Debril\RssAtomBundle\HttpDriverResponse
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

}
