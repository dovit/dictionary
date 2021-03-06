<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Word;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\Paginator;

/**
 * WordRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class WordRepository extends EntityRepository
{
    private $paginate;

    public function setPaginate(Paginator $paginate)
    {
        $this->paginate = $paginate;
    }

    /**
     * Get list of word by dictionary
     *
     * @param $dictionary
     * @param $page
     * @param $limit
     * @return array
     */
    public function fetchWordByDictionary($dictionary, $page, $limit)
    {
        $query = $this->createQueryBuilder('w')
            ->where('w.dictionary = :dictionary')
            ->setParameter('dictionary', $dictionary)
            ->orderBy('w.word')
            ->getQuery();

        return $this->paginate->paginate(
            $query,
            $page,
            $limit
        );
    }

    /**
     * Create word into dictionary
     *
     * @param Word $word
     */
    public function create(Word $word)
    {
        $this->getEntityManager()->persist($word);
        $this->getEntityManager()->flush();
    }

    /**
     * Delete a word
     *
     * @param Word $word
     */
    public function delete(Word $word)
    {
        $this->getEntityManager()->remove($word);
        $this->getEntityManager()->flush();
    }
}
