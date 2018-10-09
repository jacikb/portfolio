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
use AppBundle\Entity\Section;
use AppBundle\Form\ArticleType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormError;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ArticleController extends Controller
{

    /**
     * @Route("/", name="article_index")
     * @return Response
     */
    public function indexAction()
    {

        $entityManager = $this->getDoctrine()->getManager();

        $articles = $entityManager->getRepository(Article::class)->findPublicOrdered();
        $route = $entityManager->getRepository(Section::class)->getSectionRoute();

        return $this->render("Article/index.html.twig", ["articles" => $articles, "route"=>$route]);

    }


    /**
     * @Route("/my", name="my_article_index");
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function myAction()
    {
        $this->denyAccessUnlessGranted("ROLE_USER");

        $entityManager = $this->getDoctrine()->getManager();
        $articles = $entityManager
            ->getRepository(Article::class)
            ->findMyOrdered($this->getUser());
        //->findBy(["owner" => $this->getUser()]);

        return $this->render("MyArticle/index.html.twig", ["articles" => $articles]);
    }


    /**
     * @Route("/add", name="article_add")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return Response
     */
    public function addAction(Request $request)
    {
        $this->denyAccessUnlessGranted("ROLE_USER");

        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        if($request->isMethod("post")) {
            $form->handleRequest($request);


            if($form->isValid()){
                $article
                    ->setStatus(Article::STATUS_PUBLIC)
                    ->setOwner($this->getUser());

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($article);
                $entityManager->flush();

                $this->addFlash("success","Artykuł {$article->getTitle()} został dodany.");

            }
            $this->addFlash("error", "Nie udało się dodac artykułu");
        }
        return $this->render("MyArticle/add.html.twig", ["form" => $form->createView()]);
    }


    /**
     * @Route("/article/edit/{id}", name="article_edit" )
     * @param Request $request
     * @param Auction $auction
     * @return Response
     */
    public function editAction(Request $request, Article $article)
    {
        $this->denyAccessUnlessGranted("ROLE_USER");
        if($this->getUser() !== $article->getOwner()) {
            throw new AccessDeniedException;
        }

        $form = $this->createForm(ArticleType::class , $article);
        if($request->isMethod("post")) {
            $form->handleRequest($request);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash("success","Zapisano zmiany w artykule: {$article->getTitle()}.");
            return $this->redirectToRoute("my_article_index");
        }
        else
        {
            $deleteForm = $this->createFormBuilder()
                ->setAction($this->generateUrl("article_delete",["id" => $article->getId()]))
                ->setMethod(Request::METHOD_DELETE)
                ->add("submit", SubmitType::class, ["label" => "Usuń"])
            ->getForm();

        }
        return $this->render("MyArticle/edit.html.twig", ["form" => $form->createView(),"deleteForm" => $deleteForm->createView(),"id" => $article->getId() ]);
    }

    /**
     * @Route("/article/delete/{id}", name="article_delete", methods={"DELETE"})
     * @param Articlew $article
     * @return Response
     */
    public function deleteAction(Article $article)
    {
        $this->denyAccessUnlessGranted("ROLE_USER");
        if($this->getUser() !== $article->getOwner()) {
            throw new AccessDeniedException;
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($article);
        $entityManager->flush();

        $this->addFlash("success","Artykuł {$article->getTitle()} został usunięty.");

        return $this->redirectToRoute("article_index");
    }

    /**
     * @Route("/select/{route}", name="article_select")
     * @return Response
     */
    public function selectAction(Section $section)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $articles = $entityManager->getRepository(Article::class)->findArticleBySection($section);
        return $this->render("Article/index.html.twig", ["articles" => $articles]);

    }
}