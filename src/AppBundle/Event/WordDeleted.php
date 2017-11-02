<?php

namespace AppBundle\Event;

use AppBundle\Entity\Word;
use Symfony\Component\EventDispatcher\Event;

class WordDeleted extends Event
{
    const NAME = 'word.deleted';

    protected $word;

    public function __construct($word)
    {
        $this->word = $word;
    }

    public function getWord(): Word
    {
        return $this->word;
    }
}
