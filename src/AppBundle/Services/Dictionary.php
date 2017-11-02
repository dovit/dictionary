<?php

namespace AppBundle\Services;

use AppBundle\Entity\Dictionary as DictionaryEntity;
use AppBundle\Event\DictionaryCreated;
use AppBundle\Event\DictionaryDeleted;
use AppBundle\Repository\DictionaryRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Dictionary
{
    private $repository;
    private $dispatcher;

    /**
     * Word constructor.
     *
     * @param DictionaryRepository $repository
     */
    public function __construct(DictionaryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function setDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function create(DictionaryEntity $dictionary)
    {
        $this->repository->create($dictionary);

        $event = new DictionaryCreated($dictionary);
        $this->dispatcher->dispatch(DictionaryCreated::NAME, $event);
    }

    public function delete(DictionaryEntity $dictionary)
    {
        $this->repository->delete($dictionary);

        $event = new DictionaryDeleted($dictionary);
        $this->dispatcher->dispatch(DictionaryDeleted::NAME, $event);
    }
}
