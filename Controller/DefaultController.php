<?php

namespace Debril\RssAtomBundle\Controller;

use \DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{

    /**
     * @Template()
     */
    public function indexAction()
    {
        $reader = $this->getReader();

        $date = DateTime::createFromFormat("Y-m-d H:i:s", "2012-12-25 22:04:00");
        $url = 'https://raw.github.com/alexdebril/rss-atom-bundle/master/Resources/sample-atom.xml';

        $content = $reader->getFeedContent($url, $date);
        $response = new \Symfony\Component\HttpFoundation\Response(print_r($content, true));

        return $response;
    }

    /**
     *
     * @return Debril\RssAtomBundle\Protocol\FeedReader
     */
    protected function getReader()
    {
        return $this->get('FeedReader');
    }

}
