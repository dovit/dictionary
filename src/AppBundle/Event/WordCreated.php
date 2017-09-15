<?php

namespace AppBundle\Event;

use AppBundle\Entity\Word;
use Symfony\Component\EventDispatcher\Event;

class WordCreated extends Event
{
    const NAME = 'word.created';

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
