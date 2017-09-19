<?php

namespace AppBundle\Event;

use AppBundle\Entity\Dictionary;
use Symfony\Component\EventDispatcher\Event;

class DictionaryLoaded extends Event
{
    const NAME = 'dictionary.loaded';

    protected $dictionary;

    public function __construct($dictionary)
    {
        $this->dictionary = $dictionary;
    }

    public function getDictionary(): Dictionary
    {
        return $this->dictionary;
    }
}
