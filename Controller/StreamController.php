<?php

namespace Debril\RssAtomBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\OptionsResolver\Options;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Debril\RssAtomBundle\Provider\FeedContentProvider;
use Debril\RssAtomBundle\Exception\FeedNotFoundException;

class StreamController extends Controller
{

    /**
     * default provider
     */
    const DEFAULT_SOURCE = 'debril.provider.default';

    /**
     * parameter used to force refresh at every hit (skips 'If-Modified-Since' usage).
     * set it to true for debug purpose
     */
    const FORCE_PARAM_NAME = 'force_refresh';

    /**
     *
     * @var \DateTime
     */
    protected $since;

    /**
     * @Route("/stream/{id}")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $options = $this->buildOptions($request);
        $options->set('Since', $this->getModifiedSince());

        return $this->createStreamResponse(
                        $options, $request->get('format', 'rss'), $request->get('source', self::DEFAULT_SOURCE)
        );
    }

    /**
     * Extract the 'If-Modified-Since' value from the headers
     * @return \DateTime
     */
    public function getModifiedSince()
    {
        if (is_null($this->since))
        {
            if ($this->getRequest()->headers->has('If-Modified-Since'))
            {
                $string = $this->getRequest()->headers->get('If-Modified-Since');
                $this->since = \DateTime::createFromFormat(\DateTime::RSS, $string);
            } else
            {
                $this->since = new \DateTime;
                $this->since->setTimestamp(1);
            }
        }

        return $this->since;
    }

    /**
     * Generate the HTTP response
     * 200 : a full body containing the stream
     * 304 : Not modified
     *
     * @param \Symfony\Component\OptionsResolver\Options $options
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createStreamResponse(Options $options, $format, $source = self::DEFAULT_SOURCE)
    {
        $content = $this->getContent($options, $source);

        if ($this->mustForceRefresh() || $content->getLastModified() > $this->getModifiedSince())
        {
            $formatter = $this->getFormatter($format);
            $response = new Response($formatter->toString($content));
            $response->headers->set('Content-Type', 'application/xhtml+xml');

            $response->setPublic();
            $response->setMaxAge(3600);
            $response->setLastModified($content->getLastModified());
        } else
        {
            $response = new Response;
            $response->setNotModified();
        }

        return $response;
    }

    /**
     * Get the Stream's content using a FeedContentProvider
     * The FeedContentProvider instance is provided as a service
     * default : debril.provider.service
     *
     * @param \Symfony\Component\OptionsResolver\Options $options
     * @param string $source
     * @return \Debril\RssAtomBundle\Protocol\FeedOut
     * @throws \Exception
     */
    protected function getContent(Options $options, $source)
    {
        $provider = $this->get($source);

        if (!$provider instanceof FeedContentProvider)
        {
            throw new \Exception('Provider is not a FeedContentProvider instance');
        }

        try
        {
            return $provider->getFeedContent($options);
        } catch (FeedNotFoundException $e)
        {
            throw $this->createNotFoundException('feed not found');
        }
    }

    /**
     * Build an Options object using parameters coming from the route
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\OptionsResolver\Options
     */
    protected function buildOptions(Request $request)
    {
        $options = new Options;
        $routeParams = $request->attributes->get('_route_params');
        foreach ($routeParams as $key => $value)
            $options->set($key, $value);

        return $options;
    }

    /**
     * Returns true if the controller must ignore the last modified date
     *
     * @return boolean
     */
    protected function mustForceRefresh()
    {
        if ($this->container->hasParameter(self::FORCE_PARAM_NAME))
            return $this->container->getParameter(self::FORCE_PARAM_NAME);

        return false;
    }

    /**
     * Get the accurate formatter
     *
     * @param  string $format
     * @throws \Exception
     * @return \Debril\RssAtomBundle\Protocol\FeedFormatter
     */
    protected function getFormatter($format)
    {
        $services = array(
            'rss' => 'debril.formatter.rss',
            'atom' => 'debril.formatter.atom',
        );

        if (!array_key_exists($format, $services))
            throw new \Exception("Unsupported format {$format}");

        return $this->get($services[$format]);
    }

}
