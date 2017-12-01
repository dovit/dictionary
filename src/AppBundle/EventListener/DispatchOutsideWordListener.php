<?php

namespace AppBundle\EventListener;

use AppBundle\Event\DictionaryLoaded;
use AppBundle\Event\WordCreated;

class DispatchOutsideWordListener
{
    private $producer;
    private $serializer;
    private $logger;

    public function __construct($producer, $serializer = null, $logger)
    {
        $this->producer = $producer;
        $this->serializer = $serializer;
        $this->logger = $logger;
    }

    public function onWordCreated(WordCreated $event)
    {
        $data = $this->serializer->serialize($event->getWord(), 'json');
        $this->producer->publish($this->serializer->serialize($event->getWord(), 'json'), 'dictionary.word_created');
        $this->logger->info('send event', ['data' => $data]);
    }

    public function onDictionaryLoaded(DictionaryLoaded $event)
    {
        $data = $this->serializer->serialize($event->getDictionary(), 'json');
        $this->producer->publish($this->serializer->serialize($event->getDictionary(), 'json'), 'dictionary.created');
        $this->logger->info('send event', ['data' => $data]);
    }
}
