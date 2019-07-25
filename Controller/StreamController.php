<?php declare(strict_types=1);

namespace Debril\RssAtomBundle\Controller;

use FeedIo\FeedIo;
use FeedIo\FeedInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Debril\RssAtomBundle\Provider\FeedContentProviderInterface;
use Debril\RssAtomBundle\Exception\FeedException\FeedNotFoundException;

/**
 * Class StreamController.
 */
class StreamController extends AbstractController
{

    /**
     * @var \DateTime
     */
    protected $since;

    /**
     * @param Request $request
     * @param FeedContentProviderInterface $provider
     * @param FeedIo $feedIo
     * @return Response
     * @throws \Exception
     */
    public function indexAction(Request $request, FeedContentProviderInterface $provider, FeedIo $feedIo) : Response
    {
        $options = $request->attributes->get('_route_params');
        $this->setModifiedSince($request);
        $options['Since'] = $this->getModifiedSince();

        return $this->createStreamResponse(
            $options,
            $request->get('format', 'rss'),
            $provider,
            $feedIo
        );
    }

    /**
     * Extract the 'If-Modified-Since' value from the headers.
     *
     * @return \DateTime
     */
    protected function getModifiedSince() : \DateTime
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
    protected function setModifiedSince(Request $request) : self
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
     * @param FeedContentProviderInterface $provider
     * @param FeedIo $feedIo
     *
     * @return Response
     *
     * @throws \Exception
     */
    protected function createStreamResponse(array $options, string $format, FeedContentProviderInterface $provider, FeedIo $feedIo) : Response
    {
        $content = $this->getContent($options, $provider);

        if ($this->mustForceRefresh() || $content->getLastModified() > $this->getModifiedSince()) {
            $response = new Response($feedIo->format($content, $format));
            $this->setFeedHeaders($response, $content, $format);

        } else {
            $response = new Response();
            $response->setNotModified();
        }

        return $response;
    }

    /**
     * @param Response $response
     * @param FeedInterface $feed
     * @param string $format
     * @return $this
     */
    protected function setFeedHeaders(Response $response, FeedInterface $feed, string $format) : self
    {
        $contentType =
            'json' == $format ?
                $this->getParameter('debril_rss_atom.content_type_json') :
                $this->getParameter('debril_rss_atom.content_type_xml')
            ;
        $response->headers->set('Content-Type', $contentType);
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
     * @param FeedContentProviderInterface $provider
     *
     * @return FeedInterface
     *
     * @throws \Exception
     */
    protected function getContent(array $options, FeedContentProviderInterface $provider) : FeedInterface
    {
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
    protected function mustForceRefresh() : bool
    {
        return $this->getParameter('debril_rss_atom.force_refresh');
    }

    /**
     * @return boolean true if the feed must be private
     */
    protected function isPrivate() : bool
    {
        return $this->getParameter('debril_rss_atom.private_feeds');
    }

}
