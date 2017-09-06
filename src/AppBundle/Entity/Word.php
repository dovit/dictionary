<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Swagger\Annotations as SWG;
use JMS\Serializer\Annotation as JMS;

/**
 * Word.
 *
 * @ORM\Table(name="word")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\WordRepository")
 *
 * @SWG\Definition(
 *     definition="Word",
 *     required={"word", "dictionary"},
 *     type="object"
 * )
 */
class Word
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @JMS\Expose()
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="word", type="string", length=255)
     * @SWG\Property(readOnly=false, example="france")
     *
     * @JMS\Expose()
     */
    private $word;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Dictionary", inversedBy="words", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $dictionary;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set word.
     *
     * @param string $word
     *
     * @return Word
     */
    public function setWord($word)
    {
        $this->word = $word;

        return $this;
    }

    /**
     * Get word.
     *
     * @return string
     */
    public function getWord()
    {
        return $this->word;
    }

    /**
     * @return mixed
     */
    public function getDictionary() : Dictionary
    {
        return $this->dictionary;
    }

    /**
     * @param mixed $dictionary
     */
    public function setDictionary(Dictionary $dictionary)
    {
        $this->dictionary = $dictionary;
    }
}
