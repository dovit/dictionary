<?php

namespace AppBundle\EventListener;

use AppBundle\Event\DictionaryLoaded;
use AppBundle\Event\WordCreated;

class DispatchOutsideWordListener
{
    private $producer;
    private $serializer;

    public function __construct($producer, $serializer = null)
    {
        $this->producer = $producer;
        $this->serializer = $serializer;
    }

    public function onWordCreated(WordCreated $event)
    {
        $this->producer->publish($this->serializer->serialize($event->getWord(), 'json'), 'dictionary.word_created');
    }

    public function onDictionaryLoaded(DictionaryLoaded $event)
    {
        $this->producer->publish(json_encode($event->getDictionary()), 'dictionary.created');
    }
}
