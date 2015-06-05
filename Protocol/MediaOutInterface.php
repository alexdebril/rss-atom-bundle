<?php

namespace Debril\RssAtomBundle\Protocol;

/**
 * Interface MediaOutInterface
 */
interface MediaOutInterface
{
    /**
     * Rss  : rss.entry.media:content url attribute
     *
     * @return string
     */
    public function getUrl();

    /**
     * Rss  : rss.entry.media:content type attribute
     *
     * @return string mime type
     */
    public function getType();

    /**
     * Rss  : rss.entry.media:content fileSize attribute
     *
     * @return integer fle size or 0 if unknown
     */
    public function getLength();
}
