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
     * @deprecated removed in version 3.0
     */
    public function getUrl();

    /**
     * Rss  : rss.entry.media:content type attribute
     *
     * @return string mime type
     * @deprecated removed in version 3.0
     */
    public function getType();

    /**
     * Rss  : rss.entry.media:content fileSize attribute
     *
     * @return integer fle size or 0 if unknown
     * @deprecated removed in version 3.0
     */
    public function getLength();
}
