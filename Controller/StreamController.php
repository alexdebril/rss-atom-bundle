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
     * @param Request $request
     * @param FeedBuilder $feedBuilder
     * @param FeedProviderInterface $provider
     * @return Response
     */
    public function indexAction(Request $request, FeedBuilder $feedBuilder, FeedProviderInterface $provider) : Response
    {
        try {
            return $feedBuilder->getResponse(
                $request->get('format', 'rss'),
                $provider->getFeed($request)
            );
        } catch (FeedNotFoundException $e) {
            throw new NotFoundHttpException('feed not found');
        }
    }

}
