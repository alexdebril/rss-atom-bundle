<?php

/**
 * Rss/Atom Bundle for Symfony 2.
 *
 *
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @copyright (c) 2013, Alexandre Debril
 */
namespace Debril\RssAtomBundle\Driver;

/**
 * Class HttpDriverResponse.
 */
class HttpDriverResponse
{
    const HTTP_CODE_OK = 200;
    const HTTP_CODE_MOVE_PERMANENTLY = 301;
    const HTTP_CODE_FOUND = 302;
    const HTTP_CODE_NOT_MODIFIED = 304;
    const HTTP_CODE_FORBIDDEN = 403;
    const HTTP_CODE_NOT_FOUND = 404;
    const HTTP_CODE_SERVER_ERROR = 500;

    /**
     * @var string
     */
    protected $body;

    /**
     * @var array
     */
    protected $headers;

    /**
     * @var int
     */
    protected $httpCode;

    /**
     * @var string
     */
    protected $httpVersion;

    /**
     * @var string
     */
    protected $httpMessage;

    /**
     * @return bool
     */
    public function getHttpCodeIsOk()
    {
        return $this->getHttpCode() === self::HTTP_CODE_OK;
    }

    /**
     * @return bool
     */
    public function getHttpCodeIsRedirection()
    {
        return in_array(
            $this->getHttpCode(),
            array(
                self::HTTP_CODE_MOVE_PERMANENTLY,
                self::HTTP_CODE_FOUND,
            )
        );
    }

    /**
     * @return bool
     */
    public function getHttpCodeIsCached()
    {
        return in_array(
            $this->getHttpCode(),
            array(
                self::HTTP_CODE_NOT_MODIFIED,
            )
        );
    }

    /**
     * @return int
     */
    public function getHttpCode()
    {
        return $this->httpCode;
    }

    /**
     * @param int $httpCode
     *
     * @return HttpDriverResponse
     */
    public function setHttpCode($httpCode)
    {
        $this->httpCode = (int) $httpCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getHttpVersion()
    {
        return $this->httpVersion;
    }

    /**
     * @param string $httpVersion
     *
     * @return HttpDriverResponse
     */
    public function setHttpVersion($httpVersion)
    {
        $this->httpVersion = $httpVersion;

        return $this;
    }

    /**
     * @return string
     */
    public function getHttpMessage()
    {
        return $this->httpMessage;
    }

    /**
     * @param string $httpMessage
     *
     * @return HttpDriverResponse
     */
    public function setHttpMessage($httpMessage)
    {
        $this->httpMessage = $httpMessage;

        return $this;
    }

    /**
     * @return string
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param string $headers
     *
     * @return HttpDriverResponse
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $body
     *
     * @return HttpDriverResponse
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }
}
