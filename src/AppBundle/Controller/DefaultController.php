<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Swagger\Annotations as SWG;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;

/**
 * @SWG\Swagger(
 *     basePath="/app_dev.php"
 * )
 *
 * @SWG\Info(
 *     title="Dictionary api",
 *     version="0.1",
 * )
 *
 * @SWG\Post(
 *     description="Get a dictionary",
 *     path="/api/login_check",
 *     tags={"user"},
 *     @SWG\Parameter(
 *          name="_username",
 *          in="body",
 *          @SWG\Schema(ref="#/definitions/User")
 *     ),
 *     @SWG\Response(
 *          response="200",
 *          description="Test"
 *      )
 * )
 *
 * @SWG\Definition(
 *     definition="User",
 *     type="object",
 *     @SWG\Property(property="_username", example="Jerome"),
 *     @SWG\Property(property="_password", example="Jerome")
 * )

 */
class DefaultController extends Controller
{
    /**
     * @Route("/swagger.json", name="documentation")
     *
     * @Method({"GET"})
     */
    public function documentationAction()
    {
        $swagger = \Swagger\scan($this->get('kernel')->getRootDir() . '/../src');
        return new JsonResponse($swagger);
    }
}
