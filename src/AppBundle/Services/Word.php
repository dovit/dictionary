<?php

namespace AppBundle\Services;

use AppBundle\Entity\Dictionary;
use AppBundle\Entity\Word as WordEntity;
use AppBundle\Event\WordCreated;
use AppBundle\Event\WordDeleted;
use AppBundle\Repository\WordRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Word
{
    private $repository;
    private $dispatcher;

    /**
     * Word constructor.
     *
     * @param WordRepository $repository
     */
    public function __construct(WordRepository $repository)
    {
        $this->repository = $repository;
    }

    public function setDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function create(WordEntity $word)
    {
        $this->repository->create($word);

        $event = new WordCreated($word);
        $this->dispatcher->dispatch(WordCreated::NAME, $event);
    }

    public function delete(Word $word)
    {
        $this->repository->delete($word);

        $event = new WordDeleted($word);
        $this->dispatcher->dispatch(WordDeleted::NAME, $event);
    }

    public function fetchWordByDictionary(Dictionary $dictionary, $page = 1, $limit = 1000)
    {
        return $this->repository->fetchWordByDictionary($dictionary, $page, $limit);
    }
}
