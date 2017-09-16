<?php

namespace AppBundle\EventListener;

use AppBundle\Event\WordCreated;

class DispatchOutsideWordListener
{
    private $producer;

    public function __construct($producer)
    {
        $this->producer = $producer;
    }

    public function onWordCreated(WordCreated $event)
    {
        $this->producer->publish(json_encode($event->getWord()));
    }
}
