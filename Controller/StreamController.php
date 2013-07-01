<?php

namespace Debril\RssAtomBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\OptionsResolver\Options;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Debril\RssAtomBundle\Provider\FeedContentProvider;
use Debril\RssAtomBundle\Exception\FeedNotFoundException;

class StreamController extends Controller
{

    const DEFAULT_SOURCE = 'debril.provider.default';
    const FORCE_PARAM_NAME = 'force_refresh';

    /**
     *
     * @var \DateTime
     */
    protected $since;

    /**
     * @Route("/stream/{contentId}")
     * @Template()
     */
    public function indexAction($format, $contentId = null, $source = self::DEFAULT_SOURCE)
    {
        $options = new Options;

        $options->set('Since', $this->getModifiedSince());

        if (!is_null($contentId))
            $options->set('contentId', $contentId);

        return $this->createStreamResponse($options, $format, $source);
    }

    /**
     *
     * @return \DateTime
     */
    public function getModifiedSince()
    {
        if (is_null($this->since))
        {
            if ($this->getRequest()->headers->has('If-Modified-Since'))
            {
                $this->since = \DateTime::createFromFormat(
                                \DateTime::RSS, $this->getRequest()->headers->get('If-Modified-Since')
                );
            } else
            {
                $this->since = new \DateTime;
                $this->since->setTimestamp(1);
            }
        }

        return $this->since;
    }

    /**
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
     *
     * @param \Symfony\Component\OptionsResolver\Options $options
     * @return FeedContent
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
     *
     * @param  string $format
     * @return Debril\RssAtomBundle\Protocol\FeedFormatter
     * @throws Exception
     */
    protected function getFormatter($format)
    {
        $services = array(
            'rss' => 'debril.formatter.rss',
            'atom' => 'debril.formatter.atom',
        );

        if (!array_key_exists($format, $services))
            throw new Exception("Unsupported format {$format}");

        return $this->get($services[$format]);
    }

}
