<?php

namespace Debril\RssAtomBundle\Controller;

use \DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/hello/{name}")
     * @Template()
     */
    public function indexAction($name)
    {
        $reader = $this->getReader();

        $date = DateTime::createFromFormat("Y-m-d H:i:s", "2012-12-25 22:04:00");
        $url = 'https://raw.github.com/alexdebril/rss-atom-bundle/master/Resources/sample-atom.xml';

        $content = $reader->getFeedContent($url, $date);

        var_dump($content);
        return array('name' => $name);
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
