## Global design

rss-atom-bundle provides two sets of interfaces to read or consume RSS/Atom feeds. This is very important to know because those interfaces are the cornerstone of all exchanges between your application and the bundle

### FeedIn / ItemIn

![FeedIn / ItemIn illustration](https://raw.github.com/alexdebril/rss-atom-bundle/master/Resources/doc/feed-reading.png)

Every time you read a feed using rss-atom-bundle, the bundle will return a [FeedIn](https://github.com/alexdebril/rss-atom-bundle/blob/master/Protocol/FeedIn.php) instances with a set of [ItemIn](https://github.com/alexdebril/rss-atom-bundle/blob/master/Protocol/ItemIn.php) objects in it. Each ItemIn instance stands for an item found in the feed.

Of course, rss-atom-bundle have its own implementation of FeedIn and ItemIn in case you are not interesting in writing your own. You'll find every details in the [reading feeds section](https://github.com/alexdebril/rss-atom-bundle/wiki/Reading-feeds).