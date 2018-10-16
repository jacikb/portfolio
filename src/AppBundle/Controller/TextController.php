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
use Symfony\Component\HttpFoundation\Request;




class TextController extends Controller
{
    /**
     * @Route("/text/index/{id}", name="text_index")
     * @return Response
     */
    public function indexAction(Article $article)
    {

        return $this->render("Text/textList.html.twig");
    }
}