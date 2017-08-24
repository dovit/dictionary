<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Swagger\Annotations as SWG;
use FOS\HttpCacheBundle\Http\SymfonyResponseTagger;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

/**
 *
 * Class NamingController
 *
 */
class NamingController extends FOSRestController
{
    /**
     * @SWG\Get(
     *     description="Generate a word",
     *     path="/naming/{dictionary}/",
     *     tags={"naming"},
     *     @SWG\Parameter(
     *       name="dictionary",
     *       in="path",
     *       required=true,
     *       type="integer",
     *       description="dictionary id"
     *     ),
     *     @SWG\Response(
     *          response="200",
     *          description="Test"
     *      )
     * )
     *
     * @View()
     *
     * @Route("/naming", name="naming")
     *
     * @Method({"GET"})
     *
     */
    public function getAction()
    {
        return $this->handleView($this->view(['test' => 2], Response::HTTP_OK));
    }

    /**
     * @SWG\Get(
     *     description="Get list of dictionary",
     *     path="/naming",
     *     tags={"naming"},
     *     @SWG\Response(
     *          response="200",
     *          description="Test"
     *      )
     * )
     *
     * @View()
     *
     * @Route("/naming", name="naming")
     *
     * @Method({"GET"})
     *
     */
    public function getDictionaryAction()
    {
        return $this->handleView($this->view(['test' => 2], Response::HTTP_OK));
    }
}
