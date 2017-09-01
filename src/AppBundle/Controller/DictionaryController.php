<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Dictionary;
use AppBundle\Entity\Word;
use AppBundle\Form\DictionaryType;
use AppBundle\Form\WordType;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Swagger\Annotations as SWG;

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
     *     path="/dictionaries/",
     *     tags={"dictionary"},
     *     @SWG\Response(
     *          response="200",
     *          description="Test"
     *      )
     * )
     *
     * @View(serializerGroups={"dictionary_get"})
     *
     * @Route("/dictionaries/", name="dictionaries_get")
     *
     * @Method({"GET"})
     *
     */
    public function getAction()
    {
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
     *   path="/dictionaries/",
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
     * @Route("/dictionaries/", name="dictionary_create")
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
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($dictionary);
            $manager->flush();
            return $this->handleView($this->view([$dictionary], Response::HTTP_CREATED));
        }

        return $this->handleView($this->view($form->getErrors(), Response::HTTP_BAD_REQUEST));
    }

    /**
     * @Method({"POST"})
     *
     * @SWG\Post(
     *   path="/dictionaries/{id}/words/",
     *   summary="Create word",
     *   tags={"dictionary"},
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
     *       type="integer"
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
     * @ParamConverter("dictionary", class="AppBundle:Dictionary")
     *
     * @View()
     *
     * @Route("/dictionaries/{dictionary}/words/", name="word_create")
     * @param Dictionary $dictionary
     * @param Request $request
     * @return Response
     */
    public function createWordAction(Dictionary $dictionary, Request $request)
    {
        $word = new Word();
        $form = $this->createForm(WordType::class, $word,
            [
                'method' => 'POST',
            ]
        );

        $form->handleRequest($request);

        if ($form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $word->setDictionary($dictionary);
            $manager->persist($word);
            $manager->flush();
            return $this->handleView($this->view([$word], Response::HTTP_CREATED));
        }

        return $this->handleView($this->view($form->getErrors(), Response::HTTP_BAD_REQUEST));
    }

    /**
     * @SWG\Get(
     *     description="Get a dictionary",
     *     path="/dictionaries/{id}",
     *     tags={"dictionary"},
     *     @SWG\Parameter(
     *       name="id",
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
     * @Route("/dictionaries/{id}", name="dictionary_get")
     *
     * @Method({"GET"})
     *
     */
    public function getDictionaryAction()
    {
        return $this->handleView($this->view(['test' => 2], Response::HTTP_OK));
    }

    /**
     * @SWG\Get(
     *     description="Get words",
     *     path="/dictionaries/{id}/words/",
     *     tags={"dictionary"},
     *     @SWG\Parameter(
     *       name="id",
     *       in="path",
     *       required=true,
     *       type="integer",
     *       description="get words"
     *     ),
     *     @SWG\Response(
     *          response="200",
     *          description="Test"
     *      )
     * )
     *
     * @View()
     *
     * @Route("/dictionaries/{id}/words/", name="words_get")
     *
     * @ParamConverter("id",
     *                class="AppBundle:Dictionary",
     *                options={"mapping": {"id": "id" }} )
     *
     * @Method({"GET"})
     * @param Dictionary $dictionary
     * @return Response
     */
    public function getWordsDictionaryAction(Dictionary $dictionary)
    {
        $words = $this->get('app_word_repository')
            ->fetchWordByDictionary($dictionary, 1, 100);

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
     *     path="/dictionaries/{id}",
     *     tags={"dictionary"},
     *     @SWG\Parameter(
     *       name="id",
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
     * @Route("/dictionaries/{id}", name="dictionary_delete")
     *
     * @Method({"DELETE"})
     *
     */
    public function deleteDictionaryAction()
    {
        return $this->handleView($this->view(['test' => 2], Response::HTTP_OK));
    }
}
