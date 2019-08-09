<?php declare(strict_types=1);

namespace Debril\RssAtomBundle\Controller;

use Debril\RssAtomBundle\Provider\FeedProviderInterface;
use Debril\RssAtomBundle\Request\ModifiedSince;
use Debril\RssAtomBundle\Provider\FeedContentProviderInterface;
use Debril\RssAtomBundle\Exception\FeedException\FeedNotFoundException;
use Debril\RssAtomBundle\Response\FeedBuilder;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class StreamController.
 */
class StreamController
{

    /**
     * @var FeedBuilder
     */
    private $feedBuilder;

    /**
     * @var ModifiedSince
     */
    private $modifiedSince;

    /**
     * @param FeedBuilder $feedBuilder
     * @param ModifiedSince $modifiedSince
     */
    public function __construct(FeedBuilder $feedBuilder, ModifiedSince $modifiedSince)
    {
        $this->feedBuilder = $feedBuilder;
        $this->modifiedSince = $modifiedSince;
    }

    /**
     * @param Request $request
     * @param FeedProviderInterface $provider
     * @param LoggerInterface $logger
     * @return Response
     */
    public function indexAction(Request $request, FeedProviderInterface $provider, LoggerInterface $logger) : Response
    {
        try {
            if ($provider instanceof FeedContentProviderInterface) {
                $logger->info('The \\Debril\\RssAtomBundle\\Provider\\FeedContentProviderInterface is deprecated since rss-atom-bundle 4.3, use FeedProviderInterface instead');
                $options = $request->attributes->get('_route_params');
                $options['Since'] = $this->modifiedSince->getValue();
                $feed = $provider->getFeedContent($options);
            } else {
                $feed = $provider->getFeed($request);
            }

            return $this->feedBuilder->getResponse($request->get('format', 'rss'), $feed);
        } catch (FeedNotFoundException $e) {
            throw new NotFoundHttpException('feed not found');
        }
    }

}
