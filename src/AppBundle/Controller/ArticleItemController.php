<?php
/**
 * Created by PhpStorm.
 * User: Jacik
 * Date: 2018-10-16
 * Time: 20:34
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\Article;
use AppBundle\Entity\ArticleItem;
use AppBundle\Form\ArticleItemType;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use AppBundle\Service\FileUploader;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ArticleItemController extends Controller
{
    /**
     * @Route("/article/{id}/list", name="item_index")
     * @return Response
     */
    public function indexAction(Article $article)
    {
        $this->denyAccessUnlessGranted("ROLE_USER");
        if ($article->isAuthor($this->getUser()) == false)
            throw new AccessDeniedException;
        $entityManager = $this->getDoctrine()->getManager();
        $items = $entityManager->getRepository(ArticleItem::
        class)->
        findBy(["article" => $article]);
        return $this->render("MyArticle/itemList.html.twig", ["article" => $article, "items" => $items]);
    }

    /**
     * @Route("/article/{id}/add", name="item_add")
     * @return Response Json(message, form)
     */
    public function newAction(Article $article, Request $request, FileUploader $fileUploader)
    {
        $this->denyAccessUnlessGranted("ROLE_USER");
        if ($article->isAuthor($this->getUser()) == false)
            throw new AccessDeniedException;

        $logger = $this->get("logger");
        $articleItem = new ArticleItem();
        $form = $this->createForm(ArticleItemType::
        class , $articleItem);//, array(
        //"action" => $this->generateUrl("item_edit",["id" => $articleItem->getId()]),
        //"attr" => ["id" => "form-" . $articleItem->getId()],
        //));
        
        if ($request->isMethod("post")) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $articleItem
                    ->setArticle($article);
                if ($file = $articleItem->getPhoto()) {
                    $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                    try {
                        $file->move(
                            $this->getParameter('photo_directory'),
                            $fileName
                        );
                        $articleItem->setPhoto($fileName);
                    } catch (FileException $e) {
                        $logger->notice('UPLOAD FILE EXCEPTION', (array)$e);
                        $articleItem->setPhoto('');
                    }
                }
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($articleItem);
                $entityManager->flush();
                return $this->redirectToRoute("item_index", ["id" => $article->getId()]);
            }
        }
        
        return $this->render("MyArticle/itemAdd.html.twig", ["form" => $form->createView(), "article" => $article]);
    }

    /**
     * @Route("/article/item/edit/{id}", name="item_edit")
     * @return Response Json(message, form)
     */
    public function editAction(ArticleItem $articleItem, Request $request)
    {
        $this->denyAccessUnlessGranted("ROLE_USER");
        $logger = $this->get("logger");
        $logger->notice("editAction");
        $data = array("empty" => 1);
        $form = $this->createForm(ArticleItemType::
        class, $articleItem, array(
        "action" => $this->generateUrl("item_edit", ["id" => $articleItem->getId()]),
        "attr" => ["id" => "form-" . $articleItem->getId()],
    ));

        if ($request->isMethod("post")) {
            //$logger->notice("editAction POST REQUEST ".$request->__toString());
            if ($request->isXmlHttpRequest()) {
                $data = json_decode($request->getContent(), true);
                $form->submit($data);
            }
            //$form->handleRequest($request);
            if ($form->isValid()) {
                $file = $articleItem->getPhoto();
                //if(!empty($file)) {
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move($this->getParameter('photos_directory'), $fileName);
                $articleItem->setPhoto($fileName);
                //}
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($articleItem);
                $entityManager->flush();
                return new JsonResponse(
                    array(
                        'message' => 'ok',
                        'form' => $this->renderView("MyArticle/itemShow.html.twig",
                            array(
                                'entity' => $articleItem,
                                'item' => $articleItem,
                                'data' => $data
                            ))),
                    200);
            }
        }
        return new JsonResponse(
            array(
                'message' => 'Tryb edycji',
                'form' => $this->renderView("MyArticle/itemEdit.html.twig",
                    array(
                        'entity' => $articleItem,
                        'form' => $form->createView(),
                        'item' => $articleItem,
                        'data' => $data
                    ))),
            200);
    }

    private function JsonItemEdit($form, $data)
    {

    }

    /**
     * @Route("/article/item/show/{id}", name="item_show")
     * @return Response Json
     */
    public function showAction(ArticleItem $articleItem, Request $request)
    {
        $this->denyAccessUnlessGranted("ROLE_USER");
        $response = new JsonResponse(
            array(
                'message' => 'OK Show',
                'form' => $this->renderView("MyArticle/itemShow.html.twig",
                    array(
                        'entity' => $articleItem,
                        'item' => $articleItem,
                    ))), 200);
        return $response;
    }

    /**
     * @Route("/article/item/list/{id}", name="item_list")
     * @return Response
     */
    public function listAction(Article $article, Request $request)
    {

        $entityManager = $this->getDoctrine()->getManager();
        $items = $entityManager->getRepository(ArticleItem::
        class)->
        findBy(["article" => $article]);

        return $this->render("MyArticle/itemListDT.html.twig", ["article" => $article, "items" => $items]);

    }

}
