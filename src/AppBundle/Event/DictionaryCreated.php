<?php

namespace AppBundle\Event;

use AppBundle\Entity\Dictionary;
use Symfony\Component\EventDispatcher\Event;

class DictionaryCreated extends Event
{
    const NAME = 'dictionary.created';

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
