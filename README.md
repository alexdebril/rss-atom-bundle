RssAtomBundle - Read and Build Atom/RSS feeds
=============================================
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/9e0b1301-d7a5-49fd-916b-49da544389ac/big.png)](https://insight.sensiolabs.com/projects/9e0b1301-d7a5-49fd-916b-49da544389ac)
[![Latest Stable Version](https://poser.pugx.org/debril/rss-atom-bundle/v/stable.png)](https://packagist.org/packages/debril/rss-atom-bundle)
[![Build Status](https://secure.travis-ci.org/alexdebril/rss-atom-bundle.png?branch=master)](http://travis-ci.org/alexdebril/rss-atom-bundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/alexdebril/rss-atom-bundle/badges/quality-score.png?s=6e4cc3b9368ddbf14b1066114b6af6d9011894d9)](https://scrutinizer-ci.com/g/alexdebril/rss-atom-bundle/)
[![Code Coverage](https://scrutinizer-ci.com/g/alexdebril/rss-atom-bundle/badges/coverage.png?s=5bbd191f3b9364b8c31d8f1881f4c1fd06829fc3)](https://scrutinizer-ci.com/g/alexdebril/rss-atom-bundle/)

RssAtomBundle is a Bundle for Symfony 2 made to easily access and deliver RSS / Atom feeds. It features:

- Detection of the feed format (RSS / Atom)
- enclosures support
- A generic StreamController built to write all your feeds. This controller is able to send a 304 HTTP Code if the feed didn't change since the last visit
- HTTP Headers support when reading feeds in order to save network traffic
- Content filtering to fetch only the newest items
- multiple feeds writing
- Ability to use doctrine as a data source

Keep informed about about new releases and incoming features : http://blog.debril.fr/category/rss-atom-bundle

All classes are heavily tested using PHPUnit.

Installation
============

Dependencies
------------

As a Symfony 2 Bundle, RssAtomBundle must be installed using Composer. If you do not know Composer, please refer to its website: http://getcomposer.org/

Installation in a Symfony 2 project
-----------------------------------

This is the most common way if you want to add RssAtomBundle into an existing project.
Edit composer.json and add the following line in the "require" section:

    "debril/rss-atom-bundle": "1.6"

then, ask Composer to install it:

    composer.phar update debril/rss-atom-bundle
    
finally, edit your app/AppKernel.php to register the bundle in the registerBundles() method as above:


```php
class AppKernel extends Kernel
{

    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            // ...
            // register the bundle here
            new Debril\RssAtomBundle\DebrilRssAtomBundle(),
```

Fetching the repository
-----------------------

Do this if you want to contribute (and you're welcome to do so):

    git clone https://github.com/alexdebril/rss-atom-bundle.git

    composer.phar install --dev

Unit Testing
============

You can run the unit test suites using the following command in the Bundle's source director:

    bin/phpunit

Usage
=====

rss-atom-bundle is designed to read feeds across the internet and to publish your own. It provides two sets of interfaces, each one being dedicated to feed's consuming or publishing :

- [FeedInInterface](https://github.com/alexdebril/rss-atom-bundle/blob/master/Protocol/FeedInInterface.php) & [ItemInInterface](https://github.com/alexdebril/rss-atom-bundle/blob/master/Protocol/ItemInInterface.php) are used for feed reading.
- [FeedOutInterface](https://github.com/alexdebril/rss-atom-bundle/blob/master/Protocol/FeedOutInterface.php) & [ItemOutInterface](https://github.com/alexdebril/rss-atom-bundle/blob/master/Protocol/ItemOutInterface.php) are used for feed publishing.

Feed Reading
------------

To read a feed you need to use the `debril.reader` service which provides two methods for that : `getFeedContent()` and `readFeed()`. This service is based upon the [FeedReader](https://github.com/alexdebril/rss-atom-bundle/blob/master/Protocol/FeedReader.php) class.

## using getFeedContent()
`getFeedContent()` is designed to give a brand new FeedContent instance or any object of your own, as long as it implements the [FeedInInterface](https://github.com/alexdebril/rss-atom-bundle/blob/dev-master/Protocol/FeedInInterface.php) interface. It takes two arguments :

- `$url` : URL of the RSS/Atom feed you want to read (eg: http://php.net/feed.atom)
- `$date` : the last time you read this feed. This is useful to fetch only the articles which were published after your last hit.

Wherever you have access to the service container :
```php
<?php
    // fetch the FeedReader
    $reader = $this->container->get('debril.reader');

    // this date is used to fetch only the latest items
    $date = new \DateTime($unmodifiedSince);

    // the feed you want to read
    $url = 'http://host.tld/feed';

    // now fetch its (fresh) content
    $feed = $reader->getFeedContent($url, $date);

    // the $content object contains as many Item instances as you have fresh articles in the feed
    $items = $feed->getItems();

    foreach ( $items as $item ) {
        // getMedias() returns enclosures if any
        $medias = $item->getMedias();
    }

?>
```
`getFeedContent()` fetches the feed hosted at `$url` and removes items prior to `$date`. If it is the first time you read this feed, then you must specify a date far enough in the past to keep all the items. This method does not loop until the `$date` is reached, it justs performs one hit and filters the response to keep only the fresh articles.

If you need more information, please visit the [Reading Feeds](https://github.com/alexdebril/rss-atom-bundle/wiki/Reading-feeds) section on the wiki

Providing feeds
----------------
RssAtomBundle offers the ability to provide RSS/Atom feeds. The route will match the following pattern : /{format}/{contentId}

- {format} must be "rss" or "atom" (or whatever you want if you add the good routing rule in routing.yml)
- {contentId} is an optional argument. Use it you have several feeds

The request will be handled by `StreamController`, according to the following steps :

- 1 : grabs the ModifiedSince header if it exists
- 2 : creates an `Options` instance holding the request's parameters (contentId if it exists)
- 3 : gets the provider defined in services.xml and calls the `getFeedContent(Options $options)` method
- 4 : compare the feed's LastModified property with the ModifiedSince header
- 5 : if LastModified is prior or equal to ModifiedSince then the response contains only a "NotModified" header and the 304 code. Otherwise, the stream is built and sent to the client

StreamController expects the getFeedContent()'s return value to be a FeedOutInterface instance. It can be a Debril\RssAtomBundle\Protocol\Parser\FeedContent or a class you wrote and if so, your class MUST implement the FeedOutInterface interface.

```php
<?php
interface FeedOutInterface
{

    /**
     * Atom : feed.updated <feed><updated>
     * Rss  : rss.channel.lastBuildDate <rss><channel><lastBuildDate>
     * @return \DateTime
     */
    public function getLastModified();

    /**
     * Atom : feed.title <feed><title>
     * Rss  : rss.channel.title <rss><channel><title>
     * @return string
     */
    public function getTitle();

    // Full source can be read in the repository .......
?>
```

Now, how to plug the `StreamController` with the provider of your choice ? The easiest way is to override the `debril.provider.default` service with your own in services.xml :

```xml
<service id="debril.provider.default" class="Namespace\Of\Your\Class">
    <argument type="service" id="doctrine" />
</service>
```

Your class just needs to implement the `FeedContentProviderInterface` interface :

```php
interface FeedContentProviderInterface
{
    /**
     * @param \Symfony\Component\OptionsResolver $params
     * @return \Debril\RssAtomBundle\Protocol\FeedOutInterface
     * @throws \Debril\RssAtomBundle\Protocol\FeedNotFoundException
     */
    public function getFeedContent(Options $options);
}
```

If the reclaimed feed does not exist, you just need to throw a FeedNotFoundException to make the StreamController answer with a 404 error. Otherwise, `getFeedContent(Options $options)` must return a `FeedContent` instance, which will return an array of `Item` objects through `getItems()`. Then, the controller uses a `FeedFormatter` object to properly turn your `FeedContent` object into a XML stream.

More information on the FeedContentProviderInterface interface and how to interface rss-atom-bundle directly with doctrine can be found in the [Providing Feeds section](https://github.com/alexdebril/rss-atom-bundle/wiki/Providing-feeds)

Useful Tips
===========

Skipping 304 HTTP Code
----------------------

The HTTP cache handling can be annoying during development process, you can skip it through configuration in your app/config/parameters.yml file :

```yml
parameters:
    force_refresh:     true
```

This way, the `StreamController` will always display your feed's content and return a 200 HTTP code.

Choosing your own provider
--------------------------

Need to keep the existing routes and add one mapped to a different FeedProvider ? add it own in your routing file :

```xml
    <route id="your_route_name" pattern="/your/route/{contentId}">
        <default key="_controller">DebrilRssAtomBundle:Stream:index</default>
        <default key="format">rss</default>
        <default key="source">your.provider.service</default>
    </route>
```

The `source` parameter must contain a valid service name defined in your application.

Contributors
------------

* Alex Debril
* Elnur Abdurrakhimov https://github.com/elnur
* matdev https://github.com/matdev
