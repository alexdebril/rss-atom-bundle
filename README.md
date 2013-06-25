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

To read a feed you need to use the debril.reader service, this is rather easy :

Wherever you have access to the service container :
```
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
$reader->getFeedContent() gives you a Debril\RssAtomBundle\Protocol\FeedContent instance, the interface is defined below :
```
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
As you can see, the getItems() method will give an array of Item objects. Its inteface is as below
```
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

And if you are hitting an Atom Feed, the following methods will be available :
```
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
{format} must be "rss" or "atom" (or whatever you want if you add the good routing rule in routing.yml) and contentId is an optional argument

The request will be handled by StreamController, according to the following steps :

1 : grabs the ModifiedSince header if it exists
2 : create an "Options" instance holding the request's parameters (contentId if it exists)
3 : gets the provider defined in the services.xml and calls the getFeedContent(Options $options) method
4 : compare the content's LastModified property with the ModifiedSince header
5 : if LastModified is prior or equal to ModifiedSince then the response contains only a "NotModified" header and the 304 code. Otherwise, the feed is built and sent to the client

Now, how to plug the StreamController with the provider of your choice ? My best advice is to override the debril.provider.default service with your own in services.xml :
```
<service id="debril.provider.default" class="Namespace\Of\Your\Class">
    <argument type="service" id="doctrine" />
</service>
```

Your class just needs to implement the FeedContentProvider interface :
```
interface FeedContentProvider
{

    /**
     * @param \Symfony\Component\OptionsResolver $params
     * @throws \Debril\RssAtomBundle\Protocol\FeedNotFoundException
     */
    public function getFeedContent(Options $options);
}
```

And if the reclaimed feed does not exist, you just need to throw a FeedNotFoundException to make the StreamController answer with a 404 error.

to be continued ...