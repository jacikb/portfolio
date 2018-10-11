<?php

/**
 * Created by PhpStorm.
 * User: Jacik
 * Date: 2018-10-09
 * Time: 22:54
 */

namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\Loader\Configurator\AbstractServiceConfigurator;
use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Article;
use AppBundle\Entity\Section;
use AppBundle\Entity\User;
use AppBundle\Form\ArticleType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Forms;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ArticleService
 * @package AppBundle\Service
 */
class ArticleService extends AbstractServiceConfigurator
{
    /**
     * @var EntityManager
     */
    private $em;
    private $container;
    private $requestStack;

    private $article;
    private $form;
    private $isValid;
    private $isPost;

    public function __construct(EntityManager $entityManager, ContainerInterface $container, RequestStack $requestStack)
    {
        $this->container = $container;

        $this->em = $entityManager;
        $this->requestStack = $requestStack;
    }

    /**
     * @param Article|null $article
     * @return form
     */
    public function handleRequest(Article $article = null)
    {
        $this->isValid = false;
        $this->isPost = false;

        if($article == null) {
            $this->article = new Article();
        }
        else {
            $this->article = $article;
        }

        $formFactory = $this->container->get('form.factory');
        $this->form = $formFactory->create(ArticleType::class, $this->article);

        $request = $this->requestStack->getCurrentRequest();
        $this->isPost = $request->isMethod(Request::METHOD_POST);
        if($this->isPost()) {

            $this->isPost = true;

            $this->form->handleRequest($request);
            if($this->isValid()){

                if( empty($this->article->getOwner())) {
                    $this->article->setOwner($this->getOwner());
                }
            }
        }

        return $this->form;
    }

    /**
     * @return boolean
     */
    public function isValid()
    {
        return $this->form->isValid();
    }

    /**
     * @return mixed
     */
    public function isPost()
    {
        return  $this->isPost;
    }

    /**
     * @return form
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @return Article
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * @return Article
     */
    public function saveArticle()
    {
        $article = $this->article;
        $this->em->persist($article);
        $this->em->flush();

        return $this->article;
    }

    /**
     * @return Article
     */
    public function deleteArticle()
    {
        $article = $this->article;
        $this->em->remove($article);
        $this->em->flush();

        return $this->article;
    }

    /**
     * @return Owner
     */
    public function getOwner()
    {
        $tokenStorage = $this->container->get('security.token_storage');

        $owner = null;
        $token = $tokenStorage->getToken();
        if (null !== $token && is_object($token->getUser())) {
            $owner = $token->getUser();
        }

        return $owner;
    }

    /**
     * @return mixed
     */
    public function getPublicArticles()
    {

        return $this->em->getRepository(Article::class)
            ->findPublicOrdered();
    }

    /**
     * @return mixed
     */
    public function getMyArticles()
    {
        $owner = $this->getOwner();

        return $this->em->getRepository(Article::class)
            ->findMyOrdered($owner);
    }

    /**
     * @return mixed
     */
    public function getSectionsRoute()
    {

        return $this->em->getRepository(Section::class)
            ->getSectionRoute();
    }

    /**
     * @return mixed
     */
    public function getSectionArticle(Section $section)
    {

        return $this->em->getRepository(Section::class)
            ->findArticleBySection($section);
    }

    public function getUserArticle(User $user )
    {

        return $this->em->getRepository(Article::class)
            ->findMyOrdered($user);
    }


}