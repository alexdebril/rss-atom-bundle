<?php

namespace Debril\RssAtomBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Debril\RssAtomBundle\Provider\FeedContentProviderInterface;
use Debril\RssAtomBundle\Exception\FeedNotFoundException;

class StreamController extends Controller
{
    /**
     * default provider.
     */
    const DEFAULT_SOURCE = 'debril.provider.default';

    /**
     * parameter used to force refresh at every hit (skips 'If-Modified-Since' usage).
     * set it to true for debug purpose.
     */
    const FORCE_PARAM_NAME = 'force_refresh';

    /**
     * @var \DateTime
     */
    protected $since;

    /**
     * @Route("/stream/{id}")
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $options = $request->attributes->get('_route_params');
        $this->setModifiedSince($request);
        $options['Since'] = $this->getModifiedSince();

        return $this->createStreamResponse(
                        $options, $request->get('format', 'rss'), $request->get('source', self::DEFAULT_SOURCE)
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
     * @param array  $options
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
            $formatter = $this->getFormatter($format);
            $response = new Response($formatter->toString($content));
            $response->headers->set('Content-Type', 'application/xhtml+xml');

            $response->setPublic();
            $response->setMaxAge(3600);
            $response->setLastModified($content->getLastModified());
        } else {
            $response = new Response();
            $response->setNotModified();
        }

        return $response;
    }

    /**
     * Get the Stream's content using a FeedContentProviderInterface
     * The FeedContentProviderInterface instance is provided as a service
     * default : debril.provider.service.
     *
     * @param array                                      $options
     * @param string                                     $source
     *
     * @return \Debril\RssAtomBundle\Protocol\FeedOutInterface
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
        if ($this->container->hasParameter(self::FORCE_PARAM_NAME)) {
            return $this->container->getParameter(self::FORCE_PARAM_NAME);
        }

        return false;
    }

    /**
     * Get the accurate formatter.
     *
     * @param string $format
     *
     * @throws \Exception
     *
     * @return \Debril\RssAtomBundle\Protocol\FeedFormatter
     */
    protected function getFormatter($format)
    {
        $services = array(
            'rss' => 'debril.formatter.rss',
            'atom' => 'debril.formatter.atom',
        );

        if (!array_key_exists($format, $services)) {
            throw new \Exception("Unsupported format {$format}");
        }

        return $this->get($services[$format]);
    }
}
