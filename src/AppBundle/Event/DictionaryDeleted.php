<?php

namespace AppBundle\Event;

use AppBundle\Entity\Dictionary;
use Symfony\Component\EventDispatcher\Event;

class DictionaryDeleted extends Event
{
    const NAME = 'dictionary.deleted';

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
