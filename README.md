# RssAtomBundle - Read and Build Atom/RSS feeds

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/9e0b1301-d7a5-49fd-916b-49da544389ac/big.png)](https://insight.sensiolabs.com/projects/9e0b1301-d7a5-49fd-916b-49da544389ac)
[![Latest Stable Version](https://poser.pugx.org/debril/rss-atom-bundle/v/stable.png)](https://packagist.org/packages/debril/rss-atom-bundle)
[![Download Count](https://poser.pugx.org/debril/rss-atom-bundle/d/total)](https://packagist.org/packages/debril/rss-atom-bundle)
[![Build Status](https://secure.travis-ci.org/alexdebril/rss-atom-bundle.png?branch=master)](http://travis-ci.org/alexdebril/rss-atom-bundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/alexdebril/rss-atom-bundle/badges/quality-score.png?s=6e4cc3b9368ddbf14b1066114b6af6d9011894d9)](https://scrutinizer-ci.com/g/alexdebril/rss-atom-bundle/)
[![Code Coverage](https://scrutinizer-ci.com/g/alexdebril/rss-atom-bundle/badges/coverage.png?s=5bbd191f3b9364b8c31d8f1881f4c1fd06829fc3)](https://scrutinizer-ci.com/g/alexdebril/rss-atom-bundle/)

RssAtomBundle is a Bundle for Symfony made to easily access and deliver JSON / RSS / Atom feeds. It is built on top of [feed-io](https://github.com/alexdebril/feed-io) and features:

- Detection of the feed format (JSON / RSS / Atom)
- enclosures support
- A generic StreamController built to write all your feeds. This controller is able to send a 304 HTTP Code if the feed didn't change since the last visit
- HTTP Headers support when reading feeds in order to save network traffic
- Content filtering to fetch only the newest items
- multiple feeds writing
- Ability to use doctrine as a data source
- PSR compliant logging
- DateTime detection and conversion
- Guzzle Client integration

Keep informed about new releases and incoming features : http://debril.org/category/rss-atom-bundle

You can try rss-atom-bundle through its [Demo](https://rss-atom-demo.herokuapp.com/).

## Installation

### Dependencies

As a Symfony Bundle, RssAtomBundle must be installed using Composer. If you do not know Composer, please refer to its website: http://getcomposer.org/

### Installation in a Symfony project

This is the most common way if you want to add RssAtomBundle into an existing project.

    composer require debril/rss-atom-bundle

Add the bundle's routing configuration in app/config/routing.yml :

```yaml
rssatom:
    resource: "@DebrilRssAtomBundle/Resources/config/routing.yml"

```
#### If your application uses Symfony < 3.3

Edit your app/AppKernel.php to register the bundle in the registerBundles() method as above:


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

This step will not be necessary anymore starting from Symfony 3.3.

## Usage

rss-atom-bundle is designed to read feeds across the internet and to publish your own using [feed-io](https://github.com/alexdebril/feed-io)
feed-io provides two interfaces, each one being dedicated to feed's consuming and publishing :

- [FeedInterface](https://github.com/alexdebril/feed-io/blob/master/src/FeedIo/FeedInterface.php) to handle the feed
- [ItemInterface](https://github.com/alexdebril/feed-io/blob/master/src/FeedIo/Feed/ItemInterface.php) to handle feed's items

### Feed Reading

To read a feed you need to use the `feedio` service which provides two methods for that : `read()` and `readSince()`. This service is based upon [FeedIo](https://github.com/alexdebril/feed-io/blob/master/src/FeedIo/FeedIo.php).

#### using read()

`read()` is designed to give a brand new Feed instance or any object of your own, as long as it implements the [FeedInterface](https://github.com/alexdebril/feed-io/blob/master/src/FeedIo/FeedInterface.php) interface. It takes three arguments :

- `$url` : URL of the RSS/Atom feed you want to read (eg: http://php.net/feed.atom)
- `$feed` (optional) : a FeedInterface instance. The default is a new `\FeedIo\Feed` instance
- `$modifiedSince` (optional) : the last time you read this feed. This is useful to fetch only the articles which were published after your last hit.

Wherever you have access to the service container :
```php
<?php
    // get feedio
    $feedIo = $this->container->get('feedio');

    // this date is used to fetch only the latest items
    $modifiedSince = new \DateTime($date);

    // the feed you want to read
    $url = 'http://host.tld/feed';

    // now fetch its (fresh) content
    $feed = $feedIo->read($url, new \Acme\Entity\Feed, $modifiedSince)->getFeed();

    foreach ( $feed as $item ) {
        echo "item title : {$item->getTitle()} \n ";
        // getMedias() returns enclosures if any
        $medias = $item->getMedias();
    }

?>
```
`read()` fetches the feed hosted at `$url` and removes items prior to `$modifiedSince`. If it is the first time you read this feed, then you must specify a date far enough in the past to keep all the items. This method does not loop until the `$modifiedSince` is reached, it justs performs one hit and filters the response to keep only the fresh articles.

#### using readSince()

`readSince()` helps you get a `\FeedIo\Feed` without creating its instance :

 ```php
 <?php
     // get feedio
     $feedIo = $this->container->get('feedio');

     // this date is used to fetch only the latest items
     $modifiedSince = new \DateTime($date);

     // the feed you want to read
     $url = 'http://host.tld/feed';

     // now fetch its (fresh) content
     $feed = $feedIo->readSince($url, $modifiedSince)->getFeed();
 ?>
 ```

### Providing feeds

RssAtomBundle offers the ability to provide JSON/RSS/Atom feeds. The route will match the following pattern : /{format}/{contentId}

- {format} must be "rss" or "atom" (or whatever you want if you add the good routing rule in routing.yml)
- {contentId} is an optional argument. Use it you have several feeds

The request will be handled by `StreamController`, according to the following steps :

- 1 : grabs the ModifiedSince header if it exists
- 2 : creates an `Options` instance holding the request's parameters (contentId if it exists)
- 3 : gets the provider defined in services.yml and calls the `getFeedContent(Options $options)` method
- 4 : compare the feed's LastModified property with the ModifiedSince header
- 5 : if LastModified is prior or equal to ModifiedSince then the response contains only a "NotModified" header and the 304 code. Otherwise, the stream is built and sent to the client

StreamController expects the getFeedContent()'s return value to be a FeedInterface instance. It can be a `FeedIo\Feed` or a class you wrote and if so, your class MUST implement `\FeedIo\FeedInterface`.

```php
<?php
interface FeedInterface extends \Iterator, NodeInterface
{
    /**
     * This method MUST return the feed's full URL
     * @return string
     */
    public function getUrl();

    /**
     * @param string $url
     * @return FeedInterface
     */
    public function setUrl($url);

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
     * @return \FeedIo\FeedInterface
     * @throws \Debril\RssAtomBundle\Exception\FeedNotFoundException
     */
    public function getFeedContent(Options $options);
}
```

If the reclaimed feed does not exist, you just need to throw a FeedNotFoundException to make the StreamController answer with a 404 error. Otherwise, `getFeedContent(Options $options)` must return a `\FeedIo\FeedInterface` instance. Then, the controller properly turns the object into a XML stream.

More information on the FeedContentProviderInterface interface and how to interface rss-atom-bundle directly with doctrine can be found in the [Providing Feeds section](https://github.com/alexdebril/rss-atom-bundle/wiki/Providing-feeds)

## Override tip
It could happen that according to the order of the bundles registered in `AppKernel`, this override procedures do not work properly. This happens when a bundle is registered before `rss-atom-bundle`.
In this case, you should use the Symfony `CompilerPass` as reported in the [documentation](http://symfony.com/doc/current/bundles/override.html#services-configuration).

`Vendor/Bundle/VendorBundle.php`:
```php
use Vendor\Bundle\DependencyInjection\Compiler\OverrideRssAtomBundleProviderCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class VendorBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new OverrideRssAtomBundleProviderCompilerPass());
    }
}
```

and `Vendor/Bundle/DependencyInjection/Compiler/OverrideRssAtomBundleProviderCompilerPass.php`:
```php
use Vendor\Bundle\Provider\FeedProvider;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class OverrideRssAtomBundleProviderCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('debril.provider.default');
        $definition->setClass(FeedProvider::class);
        $definition->addArgument(new Reference('my.service1'));
        $definition->addArgument(new Reference('my.service2'));
    }
}
```

You can follow either `services.xml` or `CompilerPass` but with services, you have to pay attention to bundles registration order.


## Useful Tips

### Skipping 304 HTTP Code


The HTTP cache handling can be annoying during development process, you can skip it through configuration in your app/config/config.yml file :

```yml
debril_rss_atom:
    force_refresh: true
```

This way, the `StreamController` will always display your feed's content and return a 200 HTTP code.

### Private feeds

You may have private feeds, user-specific or behind some authentication.  
In that case, you don't want to `Cache-Control: public` header to be added, not to have your feed cached by a reverse-proxy (such as Symfony AppCache or Varnish).  
You can do so by setting `private` parameter to `true` in config:

```yml
debril_rss_atom:
    private: true
```

### Adding non-standard date formats

Some feeds use date formats which are not compliant with the specifications. You can fix this by adding the format in your configuration

```yml
# app/config/config.yml
debril_rss_atom:
    date_formats:
      - 'Y/M/d'
```

### Choosing your own provider

Need to keep the existing routes and add one mapped to a different FeedProvider ? add it own in your routing file :

```xml
    <route id="your_route_name" pattern="/your/route/{contentId}">
        <default key="_controller">DebrilRssAtomBundle:Stream:index</default>
        <default key="format">rss</default>
        <default key="source">your.provider.service</default>
    </route>
```

The `source` parameter must contain a valid service name defined in your application.

## Fetching the repository

Do this if you want to contribute (and you're welcome to do so):

    git clone https://github.com/alexdebril/rss-atom-bundle.git

    composer.phar install --dev

## Unit Testing

You can run the unit test suites using the following command in the Bundle's source director:

    bin/phpunit
