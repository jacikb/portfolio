<?php
/**
 * Created by PhpStorm.
 * User: Jacik
 * Date: 2018-10-16
 * Time: 20:34
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\Article;
use AppBundle\Entity\ArticleItem;
use AppBundle\Entity\Section;
use Symfony\Component\HttpFoundation\Request;




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
     * @Route("/article/item/edit/{id}", name="item_edit")
     * @return Response
     */
    public function editAction(ArticleItem $articleItem)
    {
        //if($article->isAuthor($this->getUser()) == false)
        //    throw new AccessDeniedException;

        //$entityManager = $this->getDoctrine()->getManager();
        //$items = $entityManager->getRepository(ArticleItem::class)->findBy(["article" => $article]);

        //new JsonResponse($response);
        return $this->render("dump.html.twig", ["data" => $articleItem]);
    }
}