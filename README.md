RssAtomBundle - Read and Build Atom/RSS feeds
=============================================

[![Latest Stable Version](https://poser.pugx.org/debril/rss-atom-bundle/v/stable.png)](https://packagist.org/packages/debril/rss-atom-bundle)

RssAtomBundle is a Bundle for Symfony 2 made to easily access and deliver RSS / Atom feeds. It features:

- Detection of the feed format (RSS / Atom)
- HTTP Headers support in order to save network traffic
- Content filtering to fetch only the newest items
- multiple feeds writing

All classes are heavily tested using PHPUnit.

Installation
============

Dependencies
------------

As a Symfony 2 Bundle, RssAtomBundle must be installed using Composer. If you do not know Composer, please refer to its website: http://getcomposer.org/

Installation in a Symfony 2 project
-----------------------------------

This is the most common way if you want to add RssAtomBundle into an existing project.
Edit compose.json and add the following line in the "require" section:

    "debril/rss-atom-bundle": "~1.0.0-rc1"

then, ask Composer to install it:

    composer.phar update debril/rss-atom-bundle


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

Reading a feed
--------------

To read a feed you need to use the `debril.reader` service. Its `getFeedContent()` method takes two arguments :

- `$url` : URL of the RSS/Atom feed you want to read (eg: http://php.net/feed.atom)
- `$date` : the last time you read this feed. This is useful to fetch only the articles which were published after your last hit.

Wherever you have access to the service container :
```php
    // fetch the FeedReader
    $reader = $this->container->get('debril.reader');

    // this date is used to fetch only the latest items
    $date = new \DateTime($unmodifiedSince);

    // the feed you want to read
    $url = 'http://host.tld/feed';

    // now fetch its (fresh) content
    $content = $reader->getFeedContent($url, $date);

    // the $content object contains as many Item instances as you have fresh articles in the feed
    $items = $content->getItems();
```
`getFeedContent()` fetches the feed hosted at `$url` and removes items prior to `$date`. If it is the first time you read this feed, then you must specify a date far enough in the past to keep all the items. This method does not loop until the `$date` is reached, it justs performs one hit and filters the response to keep only the fresh articles.

`$reader->getFeedContent()` gives you a `Debril\RssAtomBundle\Protocol\FeedContent` instance, the interface is as below :

```php
interface FeedContent
{

    /**
     * @return \DateTime
     */
    public function getLastModified();

    /**
     *
     * @return string
     */
    public function getTitle();

    /**
     * @return string
     */
    public function getDescription();

    /**
     * @return string
     */
    public function getLink();

    /**
     * @return string
     */
    public function getId();

    /**
     * @return array[\Debril\RssAtomBundle\Protocol\Item]
     */
    public function getItems();
}
```
As you can see, the `getItems()` method will give an array of `Item` objects. Its inteface is as below :

```php
interface Item
{

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @return string
     */
    public function getId();

    /**
     * @return string
     */
    public function getDescription();

    /**
     * @return DateTime
     */
    public function getUpdated();

    /**
     * @return string
     */
    public function getLink();

    /**
     * @return string
     */
    public function getAuthor();

    /**
     * @return string
     */
    public function getComment();
}

```

And if you are reading an Atom Feed, the following methods will be available :

```php
interface AtomItem
{
    /**
     * @return string
     */
    public function getSummary();

    /**
     * @return string
     */
    public function getContentType();
}
```

getSummary() returns the <summary /> node and getContentType() returns the <content /> node's type attribute

Providing feeds
----------------
RssAtomBundle offers the ability to provide RSS/Atom feeds. The route will match the following pattern : /{format}/{contentId}

- {format} must be "rss" or "atom" (or whatever you want if you add the good routing rule in routing.yml) 
- {contentId} is an optional argument. Use it you have several feeds

The request will be handled by `StreamController`, according to the following steps :

- 1 : grabs the ModifiedSince header if it exists
- 2 : creates an `Options` instance holding the request's parameters (contentId if it exists)
- 3 : gets the provider defined in services.xml and calls the `getFeedContent(Options $options)` method
- 4 : compare the content's LastModified property with the ModifiedSince header
- 5 : if LastModified is prior or equal to ModifiedSince then the response contains only a "NotModified" header and the 304 code. Otherwise, the feed is built and sent to the client

Now, how to plug the `StreamController` with the provider of your choice ? My best advice is to override the `debril.provider.default` service with your own in services.xml :

```xml
<service id="debril.provider.default" class="Namespace\Of\Your\Class">
    <argument type="service" id="doctrine" />
</service>
```

Your class just needs to implement the `FeedContentProvider` interface :

```php
interface FeedContentProvider
{
    /**
     * @param \Symfony\Component\OptionsResolver $params
     * @throws \Debril\RssAtomBundle\Protocol\FeedNotFoundException
     */
    public function getFeedContent(Options $options);
}
```

If the reclaimed feed does not exist, you just need to throw a FeedNotFoundException to make the StreamController answer with a 404 error. Otherwise, `getFeedContent(Options $options)` must return a `FeedContent` instance, which will return an array of `Item` objects through `getItems()`. Then, the controller uses a `FeedFormatter` object to properly turn your `FeedContent` object into a XML stream.

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

```
    <route id="your_route_name" pattern="/your/route/{contentId}">
        <default key="_controller">DebrilRssAtomBundle:Stream:index</default>
        <default key="format">rss</default>
        <default key="source">your.provider.service</default>
    </route>
```

The `source` parameter must contain a valid service name defined in your application.
