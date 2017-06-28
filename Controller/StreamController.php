<?php

namespace Debril\RssAtomBundle\Controller;

use FeedIo\FeedInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Debril\RssAtomBundle\Provider\FeedContentProviderInterface;
use Debril\RssAtomBundle\Exception\FeedException\FeedNotFoundException;

/**
 * Class StreamController.
 */
class StreamController extends Controller
{
    /**
     * default provider.
     */
    const DEFAULT_SOURCE = 'debril.provider.default';

    /**
     * @var \DateTime
     */
    protected $since;

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $options = $request->attributes->get('_route_params');
        $this->setModifiedSince($request);
        $options['Since'] = $this->getModifiedSince();

        return $this->createStreamResponse(
            $options,
            $request->get('format', 'rss'),
            $request->get('source', self::DEFAULT_SOURCE)
        );
    }

    /**
     * Extract the 'If-Modified-Since' value from the headers.
     *
     * @return \DateTime
     */
    protected function getModifiedSince()
    {
        if (is_null($this->since)) {
            $this->since = new \DateTime('@0');
        }

        return $this->since;
    }

    /**
     * @param Request $request
     *
     * @return $this
     */
    protected function setModifiedSince(Request $request)
    {
        $this->since = new \DateTime();
        if ($request->headers->has('If-Modified-Since')) {
            $string = $request->headers->get('If-Modified-Since');
            $this->since = \DateTime::createFromFormat(\DateTime::RSS, $string);
        } else {
            $this->since->setTimestamp(1);
        }

        return $this;
    }

    /**
     * Generate the HTTP response
     * 200 : a full body containing the stream
     * 304 : Not modified.
     *
     * @param array $options
     * @param $format
     * @param string $source
     *
     * @return Response
     *
     * @throws \Exception
     */
    protected function createStreamResponse(array $options, $format, $source = self::DEFAULT_SOURCE)
    {
        $content = $this->getContent($options, $source);

        if ($this->mustForceRefresh() || $content->getLastModified() > $this->getModifiedSince()) {
            $response = new Response($this->getFeedIo()->format($content, $format));
            $response->headers->set('Content-Type', 'application/xhtml+xml');
            $this->setFeedHeaders($response, $content);

        } else {
            $response = new Response();
            $response->setNotModified();
        }

        return $response;
    }

    /**
     * @param Response $response
     * @param FeedInterface $feed
     * @return $this
     */
    protected function setFeedHeaders(Response $response, FeedInterface $feed)
    {
        $response->headers->set('Content-Type', 'application/xhtml+xml');
        if (! $this->isPrivate() ) {
            $response->setPublic();
        }

        $response->setMaxAge(3600);
        $response->setLastModified($feed->getLastModified());

        return $this;
    }

    /**
     * Get the Stream's content using a FeedContentProviderInterface
     * The FeedContentProviderInterface instance is provided as a service
     * default : debril.provider.service.
     *
     * @param array  $options
     * @param string $source
     *
     * @return FeedInterface
     *
     * @throws \Exception
     */
    protected function getContent(array $options, $source)
    {
        $provider = $this->get($source);

        if (!$provider instanceof FeedContentProviderInterface) {
            throw new \Exception('Provider is not a FeedContentProviderInterface instance');
        }

        try {
            return $provider->getFeedContent($options);
        } catch (FeedNotFoundException $e) {
            throw $this->createNotFoundException('feed not found');
        }
    }

    /**
     * Returns true if the controller must ignore the last modified date.
     *
     * @return bool
     */
    protected function mustForceRefresh()
    {
        return $this->container->getParameter('debril_rss_atom.force_refresh');
    }

    /**
     * @return boolean true if the feed must be private
     */
    protected function isPrivate()
    {
        return $this->container->getParameter('debril_rss_atom.private_feeds');
    }

    /**
     * @return \FeedIo\FeedIo
     */
    protected function getFeedIo()
    {
        return $this->container->get('feedio');
    }

}
