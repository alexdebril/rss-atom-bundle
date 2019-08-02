<?php declare(strict_types=1);

namespace Debril\RssAtomBundle\Controller;

use Debril\RssAtomBundle\Request\ModifiedSince;
use Debril\RssAtomBundle\Response\HeadersBuilder;
use Debril\RssAtomBundle\Provider\FeedContentProviderInterface;
use Debril\RssAtomBundle\Exception\FeedException\FeedNotFoundException;
use FeedIo\FeedIo;
use FeedIo\FeedInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     * @var HeadersBuilder
     */
    private $headersBuilder;

    /**
     * @var ModifiedSince
     */
    private $modifiedSince;

    /**
     * StreamController constructor.
     * @param HeadersBuilder $headersBuilder
     * @param ModifiedSince $modifiedSince
     */
    public function __construct(HeadersBuilder $headersBuilder, ModifiedSince $modifiedSince)
    {
        $this->headersBuilder = $headersBuilder;
        $this->modifiedSince = $modifiedSince;
    }

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
        $options['Since'] = $this->modifiedSince->getValue();

        return $this->createStreamResponse(
            $options,
            $request->get('format', 'rss'),
            $provider,
            $feedIo
        );
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
        $feed = $this->getContent($options, $provider);

        if ($this->mustForceRefresh() || $feed->getLastModified() > $this->modifiedSince->getValue()) {
            $response = new Response($feedIo->format($feed, $format));
            $this->headersBuilder->setResponseHeaders($response, $format, $feed->getLastModified());

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

}
