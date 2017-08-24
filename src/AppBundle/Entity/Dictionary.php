<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Hateoas\Configuration\Annotation as Hateoas;
use Swagger\Annotations as SWG;

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
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
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
}
