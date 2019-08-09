<?php declare(strict_types=1);

namespace Debril\RssAtomBundle\Response;

use Debril\RssAtomBundle\Request\ModifiedSince;
use FeedIo\FeedInterface;
use FeedIo\FeedIo;
use Symfony\Component\HttpFoundation\Response;

class FeedBuilder
{

    /**
     * @var FeedIo
     */
    private $feedIo;

    /**
     * @var ModifiedSince
     */
    private $modifiedSince;

    /**
     * @var HeadersBuilder
     */
    private $headersBuilder;

    /**
     * @var bool
     */
    private $forceRefresh;

    /**
     * @param FeedIo $feedIo
     * @param HeadersBuilder $headersBuilder
     * @param ModifiedSince $modifiedSince
     * @param bool $forceRefresh
     */
    public function __construct(FeedIo $feedIo, HeadersBuilder $headersBuilder, ModifiedSince $modifiedSince, bool $forceRefresh = false)
    {
        $this->feedIo = $feedIo;
        $this->headersBuilder = $headersBuilder;
        $this->modifiedSince = $modifiedSince;
        $this->forceRefresh = $forceRefresh;
    }

    /**
     * Creates the HttpFoundation\Response instance corresponding to given feed
     *
     * @param string $format
     * @param FeedInterface $feed
     * @return Response
     */
    public function getResponse(string $format, FeedInterface $feed): Response
    {
        if ($this->forceRefresh || $feed->getLastModified() > $this->modifiedSince->getValue()) {
            $response = new Response($this->feedIo->format($feed, $format));
            $this->headersBuilder->setResponseHeaders($response, $format, $feed->getLastModified());
        } else {
            $response = new Response();
            $response->setNotModified();
        }

        return $response;
    }
}
