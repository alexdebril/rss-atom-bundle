<?php

namespace Debril\RssAtomBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class StreamController extends Controller
{

    /**
     * @Route("/stream")
     * @Template()
     */
    public function indexAction($contentId)
    {

        $formatter = $this->getFormatter();

        $content = $this->getContent($contentId);

        $response = new Response($formatter->toString($content));
        $response->headers->set('Content-Type', 'application/xhtml+xml');

        return $response;
    }

    /**
     *
     * @param mixed $contentId
     */
    protected function getContent($contentId)
    {
        $provider = $this->get('FeedContentProvider');

        return $provider->getFeedContentById($contentId);
    }

    /**
     *
     * @return Debril\RssAtomBundle\Protocol\FeedFormatter
     */
    protected function getFormatter()
    {
        return $this->get('FeedFormatter');
    }

}
