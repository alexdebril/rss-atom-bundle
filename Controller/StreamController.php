<?php declare(strict_types=1);

namespace Debril\RssAtomBundle\Controller;

use Debril\RssAtomBundle\Provider\FeedProviderInterface;
use Debril\RssAtomBundle\Exception\FeedException\FeedNotFoundException;
use Debril\RssAtomBundle\Response\FeedBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class StreamController
{

    /**
     * @var FeedBuilder
     */
    private $feedBuilder;

    /**
     * @param FeedBuilder $feedBuilder
     */
    public function __construct(FeedBuilder $feedBuilder)
    {
        $this->feedBuilder = $feedBuilder;
    }

    /**
     * @param Request $request
     * @param FeedProviderInterface $provider
     * @return Response
     */
    public function indexAction(Request $request, FeedProviderInterface $provider) : Response
    {
        try {
            return $this->feedBuilder->getResponse(
                $request->get('format', 'rss'),
                $provider->getFeed($request)
            );
        } catch (FeedNotFoundException $e) {
            throw new NotFoundHttpException('feed not found');
        }
    }

}
