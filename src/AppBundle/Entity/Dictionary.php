<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Hateoas\Configuration\Annotation as Hateoas;
use Swagger\Annotations as SWG;
use JMS\Serializer\Annotation as JMS;

/**
 * Dictionary.
 *
 * @ORM\Table(name="dictionary")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DictionaryRepository")
 *
 * @Hateoas\Relation(
 *      "delete",
 *      href = @Hateoas\Route(
 *          "dictionary_delete",
 *          parameters = { "id" = "expr(object.getId())" }
 *      )
 * )
 *
 * @Hateoas\Relation(
 *      "get",
 *      href = @Hateoas\Route(
 *          "dictionary_get",
 *          parameters = { "id" = "expr(object.getId())" }
 *      )
 * )
 *
 * @SWG\Definition(
 *     definition="Dictionary",
 *     required={"code"},
 *     type="object"
 * )
 */
class Dictionary
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="string")
     * @ORM\GeneratedValue(strategy="UUID")
     * @JMS\Groups({"list", "details", "dictionary_get"})
     */
    private $id;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\NotNull()
     *
     * @ORM\Column(name="title", type="string", length=255)
     *
     * @SWG\Property(readOnly=false, example="fr")
     */
    private $code;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Word", mappedBy="dictionary")
     *
     * @JMS\Groups({"get_one"})
     *
     * @JMS\Exclude()
     */
    private $words;

    public function __construct()
    {
        $this->words = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code)
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getWords()
    {
        return $this->words;
    }

    /**
     * @param mixed $words
     */
    public function setWords($words)
    {
        $this->words = $words;
    }
}
