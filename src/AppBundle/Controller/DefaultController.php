<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Swagger\Annotations as SWG;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @SWG\Swagger(
 *     basePath="/app_dev.php"
 * )
 *
 * @SWG\Info(
 *     title="Dictionary api",
 *     version="0.1",
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
