<?php

namespace AppBundle\EventListener;

use AppBundle\Event\WordCreated;

class InvalidationCacheWordListener
{
    private $cacheManager;

    public function __construct($cacheManager)
    {
        $this->cacheManager = $cacheManager;
    }

    public function onWordCreated(WordCreated $event)
    {
        $this->cacheManager->invalidateTags(
            [
                'words-list',
                'word-' . $event->getWord()->getId()
            ]
        );
    }
}
