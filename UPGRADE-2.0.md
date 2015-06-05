UPGRADE FROM 1.x to 2.0
=======================

### Exceptions namespaces

All exceptions of this bundle was moved to `Debril\RssAtomBundle\Exception` namespace.

Feed exceptions are now on `Debril\RssAtomBundle\Exception\FeedException` namespace.

Before:

```php
use Debril\RssAtomBundle\Driver\DriverUnreachableResourceException;
use Debril\RssAtomBundle\Protocol\Parser\ParserException;
use Debril\RssAtomBundle\Exception\FeedCannotBeReadException;
use Debril\RssAtomBundle\Exception\FeedNotFoundException;
use Debril\RssAtomBundle\Exception\FeedNotModifiedException;
use Debril\RssAtomBundle\Exception\FeedServerErrorException;
use Debril\RssAtomBundle\Exception\FeedForbiddenException;
```

After:

```php
use Debril\RssAtomBundle\Exception\DriverUnreachableResourceException;
use Debril\RssAtomBundle\Exception\ParserException;
use Debril\RssAtomBundle\Exception\FeedException\FeedCannotBeReadException;
use Debril\RssAtomBundle\Exception\FeedException\FeedNotFoundException;
use Debril\RssAtomBundle\Exception\FeedException\FeedNotModifiedException;
use Debril\RssAtomBundle\Exception\FeedException\FeedServerErrorException;
use Debril\RssAtomBundle\Exception\FeedException\FeedForbiddenException;
```

### Media::length

There was a typo for `Media::$lenght` field (protected access).
It now has been renamed to the correct `Media:: $length`, as its accessors, `Media::getLength()` and `Media::setLength()`.
