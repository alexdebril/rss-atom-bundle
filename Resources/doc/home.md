## Global design

rss-atom-bundle provides two sets of interfaces to read or consume RSS/Atom feeds. This is very important to know because those interfaces are the cornerstone of all exchanges between your application and the bundle

### FeedInInterface / ItemInInterface

![FeedInInterface / ItemInInterface illustration](https://raw.github.com/alexdebril/rss-atom-bundle/master/Resources/doc/feed-reading.png)

Every time you read a feed using rss-atom-bundle, the bundle will return a [FeedInInterface](https://github.com/alexdebril/rss-atom-bundle/blob/master/Protocol/FeedInInterface.php) instances with a set of [ItemInInterface](https://github.com/alexdebril/rss-atom-bundle/blob/master/Protocol/ItemInInterface.php) objects in it. Each ItemInInterface instance stands for an item found in the feed.

Of course, rss-atom-bundle have its own implementation of FeedInInterface and ItemInInterface in case you are not interesting in writing your own. You'll find every details in the [reading feeds section](https://github.com/alexdebril/rss-atom-bundle/wiki/Reading-feeds).