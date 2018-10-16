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
use AppBundle\Entity\ArticleItem;
use AppBundle\Entity\Article;
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

        return $this->render("MyArticle/itemList.html.twig", ["article" => $article]);
    }
}