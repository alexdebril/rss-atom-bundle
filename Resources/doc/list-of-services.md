List of Services
================

debril.parser.*
---------------
Debril\RssAtomBundle\Protocol\Parser\AtomParser
Debril\RssAtomBundle\Protocol\Parser\RssParser
Debril\RssAtomBundle\Protocol\Parser\RdfParser

Each parser is known as a service and is injected into the debril.reader service. You should not care about it unless you need to had your own parser.

- debril.parser.rss : RSS 2 support
- debril.parser.rdf : RDF (RSS 1.0) support
- debril.parser.atom : Atom support

Parsers must implement the \Debril\RssAtomBundle\Protocol\Parser interface and are injected through the debril.reader service using its addParser() method

debril.http.curl
----------------
Debril\RssAtomBundle\Driver\HttpCurlDriver

Service used to perform the HTTP request. It supports HTTP Headers and transforms the raw response into a HttpDriverResponse instance. It is a low-level service used by the reader and there is no reason to care about it.

debril.parser.factory
---------------------
Debril\RssAtomBundle\Protocol\Parser\Factory

This service is used to create FeedIn and ItemIn instances. The FeedIn instance is created by FeedReader and every ItemIn instances are created by the Parser.
It is possible to override the classes used to create the FeedIn and ItemIn objects through configuration :

```xml
    <parameters>
        <parameter key="debril.parser.feed.class">Debril\RssAtomBundle\Protocol\Parser\FeedContent</parameter>
        <parameter key="debril.parser.item.class">Debril\RssAtomBundle\Protocol\Parser\Item</parameter>
    </parameters>
```

debril.reader
-------------
Debril\RssAtomBundle\Protocol\FeedReader

This the main service you'll use to grab streams. It provides two methods for doing it :

 - getFeedContent() returns a brand new FeedContent instance or any object of your own, as long as it implements the FeedIn interface.
 - readFeed() hydrates an object you pass as an argument.

debril.formatter.*
------------------
Debril\RssAtomBundle\Protocol\Formatter\FeedAtomFormatter
Debril\RssAtomBundle\Protocol\Formatter\FeedRssFormatter

Each Formatter is designed to turn a FeedOut instance (and all its ItemOut) into a XML stream.

debril.provider.*
-----------------
Debril\RssAtomBundle\Provider\MockProvider

These are the data source used by the StreamController. The default is the mock provider (debril.provider.mock), so you need to override debril.provider.default's value in order to plug your own Provider as a data source for StreamController.
