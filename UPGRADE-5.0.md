# UPGRADE FROM 4.x to 5.0

## FeedContentProviderInterface is replaced with FeedProviderInterface

In version 4.x `StreamController` expects a `FeedContentProviderInterface` to provide the feed. Now it takes a `FeedProviderInterface` which is slightly different because it takes a `Request` as a parameter and no longer an array.

Before :

```php
public function getFeedContent(array $options) : FeedInterface

```

Now :

```php
public function getFeed(Request $request): FeedInterface

```

## DoctrineFeedContentProvider is removed

As its implementation is too narrow to let developers do what they need, it's better to simply remove it.

### That's it

There are no other modifications to upgrade into 5.0.
