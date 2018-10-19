<?php
/**
 * Created by PhpStorm.
 * User: Jacik
 * Date: 2018-10-06
 * Time: 15:14
 */

namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\Article;
use AppBundle\Entity\ArticleItem;
use AppBundle\Entity\Section;
use AppBundle\Entity\User;
use AppBundle\Service\ArticleService;
use AppBundle\Service\RouteService;
use AppBundle\Form\ArticleType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;



class ArticleController extends Controller
{

    /**
     * @Route("/", name="article_index")
     * @return Response
     */
    public function indexAction(ArticleService $articleService, RouteService $routeService)
    {

        $route = $routeService->getList();

        $articles = $articleService->getPublicArticles();

        return $this->render("Article/index.html.twig", ["articles" => $articles, "route"=>$route]);
    }

    /**
     * @Route("/my", name="my_article_index");
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function myAction(ArticleService $articleService, RouteService $routeService)
    {
        $this->denyAccessUnlessGranted("ROLE_USER");

        $route = $routeService->getList();

        $articles = $articleService->getMyArticles();

        return $this->render("MyArticle/index.html.twig", ["articles" => $articles, "route"=>$route]);
    }

    /**
     * @Route("/add", name="article_add")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return Response
     */
    public function addAction(ArticleService $articleService)
    {
        $this->denyAccessUnlessGranted("ROLE_USER");

        $form = $articleService->handlerequest();
        if($articleService->isPost()){
            if($articleService->isValid()){
                $articleService->saveArticle();
                $this->addFlash("success","Artykuł  został dodany.");

                return $this->redirectToRoute('my_article_index');
            }else {

                $this->addFlash("error", "Popraw błędy w formularzu");
            }
        }

        return $this->render("MyArticle/add.html.twig", ["form" => $form->createView()]);
    }

    /**
     * @Route("/article/edit/{id}", name="article_edit" )
     * @param Request $request
     * @param Auction $auction
     * @return Response
     */
    public function editAction(Request $request, ArticleService $articleService, Article $article)
    {
        $this->denyAccessUnlessGranted("ROLE_USER");

        if($article->isAuthor($this->getUser()) == false)
            throw new AccessDeniedException;


        $form = $articleService->handlerequest($article);

        if($articleService->isPost()){
            if($articleService->isValid()){
                $articleService->saveArticle();

                $this->addFlash("success","Zapisano zmiany w artykule: {$article->getTitle()}.");

                return $this->redirectToRoute('my_article_index');
            }else {
                $this->addFlash("error", "Popraw błędy w formularzu");
            }
        }
        else
        {
            /** Do przeniesienia do serwisu */
            $deleteForm = $this->createFormBuilder()
                ->setAction($this->generateUrl("article_delete",["id" => $article->getId()]))
                ->setMethod(Request::METHOD_DELETE)
                ->add("submit", SubmitType::class, ["label" => "Usuń"])
                ->getForm();

            /** Do przeniesienia do serwisu */
            $itemForm = $this->createFormBuilder()
                ->setAction($this->generateUrl("item_index",["id" => $article->getId()]))
                ->setMethod(Request::METHOD_POST)
                ->add("submit", SubmitType::class, ["label" => "Lista"])
                ->getForm();
        }

        return $this->render("MyArticle/edit.html.twig", [
            "form" => $form->createView(),
            "deleteForm" => $deleteForm->createView(),
            "itemForm" => $itemForm->createView(),
            "id" => $article->getId(),
            "article" => $article,//for dump var
        ]);
    }


    /**
     * @Route("/article/delete/{id}", name="article_delete", methods={"DELETE"})
     * @param Articlew $article
     * @return Response
     */
    public function deleteAction(Article $article, ArticleService $articleService)
    {
        $this->denyAccessUnlessGranted("ROLE_USER");

        if($article->isAuthor($this->getUser()) == false){
            throw new AccessDeniedException;
        }

        $articleService->deleteArticle($article);

        $this->addFlash("success","Artykuł {$article->getTitle()} został usunięty.");

        return $this->redirectToRoute("article_index");
    }


    /**
     * @Route("/user/{username}", name="article_user")
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function userAction(User $user, ArticleService $articleService)
    {
        $this->denyAccessUnlessGranted("ROLE_USER");

        $articles = $articleService->getUserArticle($user);

        return $this->render("MyArticle/index.html.twig", ["articles" => $articles]);
    }

    /**
     * @Route("/pdf", name="pdf")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function pdfAction(ArticleService $articleService)
    {
        $articles = $articleService->getPublicArticles();

        $snappy = $this->get("knp_snappy.pdf");
        $html = $this->renderView('Pdf/test.html.twig', array(
            'title' => 'Test pdf',
            "articles" => $articles,
        ));
        
        $file_name = "test_pdf";

        return new Response(
            $snappy->getOutputFromHtml($html),
            200,
            array(
                'Content-type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $file_name  . '.pdf"',
            )
        );
    }

}