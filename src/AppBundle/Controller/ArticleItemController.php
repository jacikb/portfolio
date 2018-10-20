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
//use AppBundle\Entity\Section;
use AppBundle\Form\ArticleItemType;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;



class ArticleItemController extends Controller
{
    /**
     * @Route("/article/item/{id}", name="item_index")
     * @return Response
     */
    public function indexAction(Article $article)
    {
        if($article->isAuthor($this->getUser()) == false)
            throw new AccessDeniedException;

        $entityManager = $this->getDoctrine()->getManager();
        $items = $entityManager->getRepository(ArticleItem::class)->findBy(["article" => $article]);

        return $this->render("MyArticle/itemList.html.twig", ["article" => $article, "items" => $items]);
    }
    /**
     * @Route("/article/item/edithold/{id}", name="item_editold")
     * @return Response
     */
    public function edithOldAction(ArticleItem $articleItem, Request $request)
    {

        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array('message' => 'You can access this only using Ajax!', 'form' => 'EMPTY FORM'), 400);
        }

        $form = $this->createForm(ArticleItemType::class , $articleItem, array(
                    "action" => $this->generateUrl("item_edit",["id" => $articleItem->getId()])
            ));


        if($request->isMethod("post")) {
            $form->handleRequest($request);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($articleItem);
            $entityManager->flush();

            //$this->get("event_dispatcher")->dispatch(Events::AUCTION_EDIT, new AuctionEvent($auction));

            //$this->addFlash("success","Zapisa zmiany w pozycji {$articleItem->getTitle()}.");

            return $this->render("MyArticle/itemShow.html.twig", ["item" => $articleItem]);
        }

        return $this->render("MyArticle/itemEdit.html.twig", ["form" => $form->createView(), "item" => $articleItem]);
    }

    /**
     * @Route("/article/item/edit/{id}", name="item_edit")
     * @return Response Json(mssage, form)
     */
    public function editAction(ArticleItem $articleItem, Request $request)
    {
        $logger = $this->get("logger");
        $logger->notice("editAction");

        if (!$request->isXmlHttpRequest()) {
            $logger->notice("editAction not json");
            return new JsonResponse(array('message' => 'You can access this only using Ajax!', 'form' => ''), 400);
        }

        $data = array("empty" => 1);


        $form = $this->createForm(ArticleItemType::class , $articleItem, array(
                        "action" => $this->generateUrl("item_edit",["id" => $articleItem->getId()]),
                        "attr" => ["id" => "form-" . $articleItem->getId()],


                    ));

        if($request->isMethod("post")) {
            //$logger->notice("editAction POST REQUEST ".$request->__toString());

            $data = json_decode($request->getContent(), true);


            $form->submit($data);
            //$form->handleRequest($request);

            if($form->isValid()) {
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
                            ))), 200);
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
                        ))), 200);
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
}