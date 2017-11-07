<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Dictionary;
use AppBundle\Entity\Word;
use AppBundle\Form\Type\DictionaryType;
use AppBundle\Form\Type\WordType;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use AppBundle\Services\Dictionary as DictionaryService;
use AppBundle\Services\Word as WordService;
use FOS\HttpCacheBundle\Http\SymfonyResponseTagger;

/**
 *
 * Class DictionaryController
 *
 */
class DictionaryController extends FOSRestController
{
    /**
     * @SWG\Get(
     *     description="Get a dictionary",
     *     path="/api/dictionaries/",
     *     tags={"dictionary"},
     *     @SWG\Parameter(
     *          name="Authorization",
     *          in="header",
     *          type="string"
     *     ),
     *     @SWG\Response(
     *          response="200",
     *          description="List of dictionaries"
     *      ),
     *     @SWG\Response(
     *          response="401",
     *          description="Unauthorized"
     *      )
     * )
     *
     * @View(serializerGroups={"dictionary_get"})
     *
     * @Route("/api/dictionaries/", name="dictionaries_get")
     *
     * @Method({"GET"})
     *
     * @return Response
     */
    public function getAction()
    {
        $this->get('fos_http_cache.http.symfony_response_tagger')->addTags(['dictionaries']);

        $dictionaries = $this->get('doctrine')
            ->getManager()
            ->getRepository('AppBundle:Dictionary')
            ->findAll();

        return $this->handleView($this->view($dictionaries, Response::HTTP_OK));
    }

    /**
     * @Method({"POST"})
     *
     * @SWG\Post(
     *   path="/api/dictionaries/",
     *   summary="Create an dictionary",
     *   tags={"dictionary"},
     *   description="Create an dictionary",
     *   @SWG\Parameter(
     *       name="body",
     *       in="body",
     *       description="Dictionary object",
     *       required=true,
     *       @SWG\Schema(ref="#/definitions/Dictionary")
     *   ),
     *   @SWG\Parameter(
     *       name="Authorization",
     *       in="header",
     *       type="string"
     *   ),
     *   @SWG\Response(
     *     response=201,
     *     description="Dictionary created"
     *   ),
     *   @SWG\Response(
     *     response=400,
     *     description="Bad request"
     *   )
     * )
     *
     * @View()
     *
     * @Route("/api/dictionaries/", name="dictionary_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createDictionaryAction(Request $request)
    {
        $dictionary = new Dictionary();
        $form = $this->createForm(DictionaryType::class, $dictionary,
            [
                'method' => 'POST',
            ]
        );

        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->get(DictionaryService::class)->create($dictionary);
            return $this->handleView($this->view([$dictionary], Response::HTTP_CREATED));
        }

        return $this->handleView($this->view($form->getErrors(), Response::HTTP_BAD_REQUEST));
    }

    /**
     * @Method({"POST"})
     *
     * @SWG\Post(
     *   path="/api/dictionaries/{id}/words/",
     *   summary="Create word",
     *   tags={"word"},
     *   description="Create word",
     *   @SWG\Parameter(
     *       name="body",
     *       in="body",
     *       description="Dictionary object",
     *       required=true,
     *       @SWG\Schema(ref="#/definitions/Word")
     *   ),
     *   @SWG\Parameter(
     *       name="id",
     *       in="path",
     *       description="Dictionary",
     *       required=true,
     *       type="string"
     *   ),
     *   @SWG\Parameter(
     *       name="Authorization",
     *       in="header",
     *       type="string"
     *     ),
     *   @SWG\Response(
     *     response=201,
     *     description="Dictionary created"
     *   ),
     *   @SWG\Response(
     *     response=400,
     *     description="Bad request"
     *   )
     * )
     *
     * @ParamConverter("dictionary", class="AppBundle:Dictionary")
     *
     * @View()
     *
     * @Route("/api/dictionaries/{dictionary}/words/", name="word_create")
     * @param Dictionary $dictionary
     * @param Request $request
     * @return Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function createWordAction(Dictionary $dictionary, Request $request)
    {
        if (null === $dictionary) {
            throw new NotFoundHttpException();
        }

        $word = new Word();
        $form = $this->createForm(WordType::class, $word,
            [
                'method' => 'POST',
            ]
        );
        $form->handleRequest($request);

        if ($form->isValid()) {
            $word->setDictionary($dictionary);
            $this->get(WordService::class)->create($word);
            return $this->handleView($this->view([$word], Response::HTTP_CREATED));
        }

        return $this->handleView($this->view($form->getErrors(), Response::HTTP_BAD_REQUEST));
    }

    /**
     * @SWG\Get(
     *     description="Get a dictionary",
     *     path="/api/dictionaries/{id}",
     *     tags={"dictionary"},
     *     @SWG\Parameter(
     *       name="id",
     *       in="path",
     *       required=true,
     *       type="string",
     *       description="dictionary id"
     *     ),
     *     @SWG\Parameter(
     *       name="Authorization",
     *       in="header",
     *       type="string"
     *     ),
     *     @SWG\Response(
     *       response="200",
     *       description="Test"
     *      )
     * )
     *
     * @View()
     *
     * @Route("/api/dictionaries/{id}", name="dictionary_get")
     * @ParamConverter("dictionary", class="AppBundle:Dictionary")
     *
     * @Method({"GET"})
     * @param Dictionary $dictionary
     * @return Response
     */
    public function getDictionaryAction(Dictionary $dictionary)
    {
        $this->get('fos_http_cache.http.symfony_response_tagger')->addTags(['dictionaries-'.$dictionary->getId()]);

        return $this->handleView($this->view($dictionary), Response::HTTP_OK);
    }

    /**
     * @SWG\Get(
     *     description="Get words",
     *     path="/api/dictionaries/{id}/words/",
     *     tags={"word"},
     *     @SWG\Parameter(
     *       name="id",
     *       in="path",
     *       required=true,
     *       type="string",
     *       description="get words"
     *     ),
     *     @SWG\Parameter(
     *       name="X-page",
     *       in="header",
     *       required=true,
     *       type="integer",
     *       description="get words"
     *     ),
     *     @SWG\Parameter(
     *       name="Authorization",
     *       in="header",
     *       type="string"
     *     ),
     *     @SWG\Response(
     *       response="200",
     *       description="Test"
     *     )
     * )
     *
     * @View()
     *
     * @Route("/api/dictionaries/{id}/words/", name="words_get")
     *
     * @ParamConverter("id",
     *                class="AppBundle:Dictionary",
     *                options={"mapping": {"id": "id" }} )
     *
     * @Method({"GET"})
     * @param Request $request
     * @param Dictionary $dictionary
     * @return Response
     */
    public function getWordsDictionaryAction(Request $request, Dictionary $dictionary)
    {
        $words = $this->get(WordService::class)
            ->fetchWordByDictionary($dictionary, $request->headers->get('X-page'), 100);

        $response = $this->handleView(
            $this->view($words->getItems(), 200)
        );
        $response->headers->add(
            [
                'X-page-count'              => $words->getPageCount(),
                'X-current-page-number'     => $words->getCurrentPageNumber(),
                'X-item-number-per-page'    => $words->getItemNumberPerPage(),
                'X-total-item'              => $words->getTotalItemCount(),
            ]
        );
        return $response;
    }

    /**
     * @SWG\Delete(
     *     description="Delete a dictionary",
     *     path="/api/dictionaries/{id}",
     *     tags={"dictionary"},
     *     @SWG\Parameter(
     *       name="id",
     *       in="path",
     *       required=true,
     *       type="string",
     *       description="dictionary id"
     *     ),
     *     @SWG\Parameter(
     *       name="Authorization",
     *       in="header",
     *       type="string"
     *     ),
     *     @SWG\Response(
     *       response="200",
     *       description="Test"
     *     )
     * )
     *
     * @ParamConverter("id",
     *                class="AppBundle:Dictionary",
     *                options={"mapping": {"id": "id" }} )
     *
     * @View()
     *
     * @Route("/api/dictionaries/{id}", name="dictionary_delete")
     *
     * @Method({"DELETE"})
     *
     * @param Dictionary $dictionary
     *
     * @return Response
     */
    public function deleteDictionaryAction(Dictionary $dictionary)
    {
        $this->get(DictionaryService::class)->delete($dictionary);
        return $this->handleView($this->view(
            null,
            RESPONSE::HTTP_NO_CONTENT
        ));
    }
}
