<?php declare(strict_types=1);

/**
 * RssAtomBundle.
 *
 *
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @copyright (c) 2013, Alexandre Debril
 *
 * creation date : 31 mars 2013
 */
namespace Debril\RssAtomBundle\Provider;

use Debril\RssAtomBundle\Exception\FeedException\FeedNotFoundException;
use FeedIo\FeedInterface;

/**
 * @deprecated since 4.3 you MUST use `FeedProviderInterface` instead
 * Interface FeedContentProviderInterface.
 */
interface FeedContentProviderInterface extends FeedProviderInterface
{
    /**
     * @param array $options
     *
     * @throws FeedNotFoundException
     *
     * @return FeedInterface
     */
    public function getFeedContent(array $options) : FeedInterface;
}
