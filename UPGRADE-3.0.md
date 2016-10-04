# UPGRADE FROM 2.x to 3.0

## use feedio service instead of debril.reader

Replace

```php
$reader = $this->container->get('debril.reader');

$feed = $reader->getFeedContent($url, $date);

```

With

```php
$feedIo = $this->container->get('feedio');

$feed = $feedIo->readSince($url, $date)->getFeed();
```

## use \FeedIo\Feed instead of \Protocol\FeedContent

Replace

```php
use Debril\RssAtomBundle\Protocol\Parser\FeedContent;
```

```php
$feed = new FeedContent();
```

With

```php
use FeedIo\Feed;
```

```php
$feed = new Feed();
```

## use \FeedIo\FeedInterface instead of \Protocol\FeedInInterface and \Protocol\FeedOutInterface

Replace

```php
use Debril\RssAtomBundle\Protocol\FeedOutInterface;
use Debril\RssAtomBundle\Protocol\FeedInInterface;
```

With

```php
use FeedIo\FeedInterface;
```

### \FeedIo\FeedInterface is an iterator

Replace

```php
$items = $feed->getItems();
foreach ( $items as $item ) {
    echo $item->getTitle();
    // ...
}
```

With

```php
foreach ( $feed as $item ) {
    echo $item->getTitle();
    // ...
}
```

## use \FeedIo\ItemInterface instead of \Protocol\ItemInInterface and \Protocol\ItemOutInterface

Replace

```php
use Debril\RssAtomBundle\Protocol\ItemOutInterface;
use Debril\RssAtomBundle\Protocol\ItemInInterface;
```

With

```php
use FeedIo\Feed\ItemInterface;
```

### ItemInterface::getLastModified() instead of ItemOutInterface::getUpdated()


```php
$items = $feed->getItems();
foreach ( $items as $item ) {
    $date = $item->getUpdated();
    // ...
}
```

```php
foreach ( $feed as $item ) {
    $date = $item->getLastModified();
    // ...
}
```

### getAuthor() and getComment() are removed

use getElement($name) instead