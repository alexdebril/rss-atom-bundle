RssAtomBundle
=============

RssAtomBundle is a Bundle for Symfony 2 made to easily access RSS and Atom feeds. It features:

- Detection of the feed format (RSS / Atom)
- HTTP Headers support in order to save network traffic
- Content filtering to fetch only the newest items

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

    "debril/rss-atom-bundle": "~0.5"

then, ask Composer to install it:

    composer.phar update debril/rss-atom-bundle

Assuming that you want to install the version 0.5 or later. Each version has its own tag, you may want to browse the versions list available on Packagist: https://packagist.org/packages/debril/rss-atom-bundle


Fetching the repository
-----------------------

Do this if you want to contribute (and you're welcome to do so):

    git clone https://github.com/alexdebril/rss-atom-bundle.git

    composer.phar install --dev

Unit Testing
============

You can run the unit test suites using the following command in the Bundle's source director:

    bin/phpunit

Documentation
=============

Documentation is one of the biggest priorities to come in the upcoming versions as for now you need to read the code in order to figure out how to use it. However, Dependency Injection is already configured to provide a fully operating service.

In a Symfony class extending the Controller class:

    // fetch the FeedReader
    $reader = $this->get('FeedReader');

    // this date is used to fetch only the latest items
    $date = new \DateTime($unmodifiedSince);

    // the feed you want to read
    $url = 'http://host.tld/feed';

    // now fetch its (fresh) content
    $content = $reader->getFeedContent($url, $date);

See https://github.com/alexdebril/rss-atom-bundle/blob/master/Protocol/FeedContent.php to understand how to use this class

What's next
===========

These are the very next priorities:

* Documentation
* RSS/Atom write support
* 100% Code coverage

Maybe other features will be implemented afterwards, like data source management
