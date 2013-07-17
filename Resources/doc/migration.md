# Migrations

## From 1.1.6 to 1.2.0

Removed interfaces :

- Debril\RssAtomBundle\Protocol\AtomItem : not replaced, the getSummary() moved into FeedOut
- Debril\RssAtomBundle\Protocol\FeedContent : replaced by Debril\RssAtomBundle\Protocol\FeedOut
- Debril\RssAtomBundle\Protocol\Item : replaced by Debril\RssAtomBundle\Protocol\ItemOut

Removed methods :

- FeedContent::getId() is replaced by FeedOut::getPublicId()
- Item::getId() is replaced by ItemOut::getPublicId()

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
