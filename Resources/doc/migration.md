# Migrations

## From 1.1.6 to 1.2.0

Removed interfaces :

- Debril\RssAtomBundle\Protocol\AtomItem : not replaced, the getSummary() moved into FeedOutInterface
- Debril\RssAtomBundle\Protocol\FeedContent : replaced by Debril\RssAtomBundle\Protocol\FeedOutInterface
- Debril\RssAtomBundle\Protocol\Item : replaced by Debril\RssAtomBundle\Protocol\ItemOutInterface

Removed methods :

- FeedContent::getId() is replaced by FeedOutInterface::getPublicId()
- Item::getId() is replaced by ItemOutInterface::getPublicId()

## From 1.2.0 to 1.2.1

`contentId` becomes `id` in the routing rules. As a consequence, it is renamed the same way in the Options object sent to your provider.

```php
<?php
    public function getFeedContent(Options $options)
    {
        $feed = $this->getFeed($options->get('contentId'));
?>
```

becomes :

```php
<?php
    public function getFeedContent(Options $options)
    {
        $feed = $this->getFeed($options->get('id'));
?>
```
