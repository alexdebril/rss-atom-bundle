<?php declare(strict_types=1);

namespace Debril\RssAtomBundle\Response;


use Symfony\Component\HttpFoundation\Response;

class HeadersBuilder
{

    const DEFAULT_MAX_AGE = 3600;

    const FORMAT_XML = 'xml';

    const DEFAULT_XML_CONTENT_TYPE = 'application/xhtml+xml';

    /**
     * supported content-types
     * @var array
     */
    private $contentTypes = [
        self::FORMAT_XML => self::DEFAULT_XML_CONTENT_TYPE
    ];

    /**
     * if true, the response is marked s public
     * @var bool
     */
    private $public;

    /**
     * maximum amount of time before the cache gets invalidated (in seconds)
     * @var int
     */
    private $maxAge;

    /**
     * HeadersBuilder constructor.
     * @param $public
     * @param $maxAge
     */
    public function __construct(bool $public = true, int $maxAge = self::DEFAULT_MAX_AGE)
    {
        $this->public = $public;
        $this->maxAge = $maxAge;
    }

    public function setContentType(string $format, $value): void
    {
        $this->contentTypes[$format] = $value;
    }

    public function setResponseHeaders(Response $response, string $format, \DateTime $lastModified): void
    {
        $response->headers->set('Content-Type', $this->getContentType($format));

        $this->public ? $response->setPublic() : $response->setPrivate();

        $response->setMaxAge($this->maxAge);
        $response->setLastModified($lastModified);
    }

    private function getContentType(string $format): string
    {
        return $this->contentTypes[$format] ?? $this->contentTypes[self::FORMAT_XML];
    }

}
