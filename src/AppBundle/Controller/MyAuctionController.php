<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Jacik2
 * Date: 25.09.18
 * Time: 12:54
 * To change this template use File | Settings | File Templates.
 */

namespace AppBundle\Controller;



use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Auction;
use AppBundle\Form\AuctionType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormError;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Validator\Constraints\DateTime;
use AppBundle\EventDispatcher\Events;
use AppBundle\EventDispatcher\AuctionEvent;



class MyAuctionController extends Controller
{
    /**
     * @Route("/my", name="my_auction_index");
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $this->denyAccessUnlessGranted("ROLE_USER");

        $entityManager = $this->getDoctrine()->getManager();
        $auctions = $entityManager
            ->getRepository(Auction::class)
            ->findMyOrdered($this->getUser());
            //->findBy(["owner" => $this->getUser()]);

        return $this->render("MyAuction/index.html.twig", ["auctions" => $auctions]);
    }

    /**
     * @Route("/my/auction/details/{id}", name="my_auction_details")
     * @param Auction $auction
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function detailsAction(Auction $auction)
    {
        $this->denyAccessUnlessGranted("ROLE_USER");
        if($auction->getStatus() === Auction::STATUS_FINISHED)
        {
            return $this->render("MyAuction/finished.html.twig",["auction" => $auction]);
        }

        //$entityManager = $this->getDoctrine()->getManager();
        //$auction = $entityManager->getRepository(Auction::class)->findOneBy(["id" => $id]);
        $deleteForm = $this->createFormBuilder()
            ->setAction($this->generateUrl("my_auction_delete",["id" => $auction->getId()])) //JAKA KACJE MA WYWOLAC  generateUrl - wygenerouje route do akcji auction_delete
            ->setMethod("DELETE") //lub podać stałą: Request::METHOD_DELETE
            ->add("submit", SubmitType::class, ["label" => "Usuń"])
            ->getForm();

        $finishForm = $this->createFormBuilder()
            ->setAction($this->generateUrl("my_auction_finish",["id" => $auction->getId()])) //JAKA KACJE MA WYWOLAC  generateUrl - wygenerouje route do akcji auction_delete
            ->setMethod(Request::METHOD_POST) //lub podać "POST"
            ->add("submit", SubmitType::class, ["label" => "Zakończ"])
            ->getForm();

        return $this->render(
            "MyAuction/details.html.twig", [
                "auction" => $auction,
                "deleteForm" => $deleteForm->createView(),
                "finishForm" => $finishForm->createView(),
            ]
        );
    }


    /**
     * @Route("/my/auction/add", name="my_auction_add")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return Response
     */
    public function addAction(Request $request)
    {
        $this->denyAccessUnlessGranted("ROLE_USER");

        $auction = new Auction();
        $form = $this->createForm(AuctionType::class, $auction);

        if($request->isMethod("post")) {
            $form->handleRequest($request);

            if($auction->getStartingPrice() >= $auction->getPrice()){
                //po dodaniu addError  isValid wykryj błąd
                $form
                    ->get("startingPrice")
                    ->addError(
                        new FormError("Cena początkowa nie może być większa od ceny kup teraz")
                    );

            }


            if($form->isValid()){
                $auction
                    ->setStatus(Auction::STATUS_ACTIVE)
                    ->setOwner($this->getUser());
                //->setCreatedAt(new \DateTime())
                //->setUpdatedAt(new \DateTime())

                $entityManager = $this->getDoctrine()->getManager();//pobiera menadzera
                $entityManager->persist($auction);//ma zapisac do bazy
                $entityManager->flush();

                $this->get("event_dispatcher")->dispatch(Events::AUCTION_ADD, new AuctionEvent($auction));

                $this->addFlash("success","Aukcja {$auction->getTitle()} zostałą dodana.");//success typ

                return $this->redirectToRoute("my_auction_details", ["id"=> $auction->getId()]);
            }
            $this->addFlash("error", "Nie udało się dodac aukcji");
        }
        return $this->render("MyAuction/add.html.twig", ["form" => $form->createView()]);
    }



    /**
     * @Route("/my/auction/edit/{id}", name="my_auction_edit")
     * @param Request $request
     * @param Auction $auction
     * @return Response
     */
    public function editAction(Request $request, Auction $auction)
    {
        $this->denyAccessUnlessGranted("ROLE_USER");
        if($this->getUser() !== $auction->getOwner()) {
            throw new AccessDeniedException;
        }

        $form = $this->createForm(AuctionType::class , $auction);//formularz wypelni si danymi z bazy danych
        if($request->isMethod("post")) {
            $form->handleRequest($request);
            //$auction->setUpdatedAt(new \DateTime());//jwest w Timestampable

            $entityManager = $this->getDoctrine()->getManager();//pobiera menadzera
            $entityManager->persist($auction);//ma zapisac do bazy
            $entityManager->flush();//fizyznie zapisuje do bazy

            $this->get("event_dispatcher")->dispatch(Events::AUCTION_EDIT, new AuctionEvent($auction));

            $this->addFlash("success","Zapisa zmiany w aukcji {$auction->getTitle()}.");
            return $this->redirectToRoute("my_auction_details", ["id"=> $auction->getId()]);
        }
        return $this->render("MyAuction/edit.html.twig", ["form" => $form->createView()]);
    }

    /**
     * @Route("/my/auction/delete/{id}", name="my_auction_delete", methods={"DELETE"})
     * @param Auction $auction
     * @return Response
     */
    public function deleteAction(Auction $auction)
    {
        $this->denyAccessUnlessGranted("ROLE_USER");
        if($this->getUser() !== $auction->getOwner()) {
            throw new AccessDeniedException;
        }

        $entityManager = $this->getDoctrine()->getManager();//pobiera menadzera
        $entityManager->remove($auction);//Usuwa rekord
        $entityManager->flush();//fizyznie zapisuje do bazy

        $this->get("event_dispatcher")->dispatch(Events::AUCTION_DELETE, new AuctionEvent($auction));

        $this->addFlash("success","Aukcja {$auction->getTitle()} została usunięta.");

        return $this->redirectToRoute("my_auction_index");
    }

    /**
     * @Route("/my/auction/finish/{id}", name="my_auction_finish", methods={"POST"})
     * @param Auction $auction
     * @return response
     */
    public function finishAction(Auction $auction)
    {
        $this->denyAccessUnlessGranted("ROLE_USER");
        if($this->getUser() !== $auction->getOwner()) {
            throw new AccessDeniedException;
        }

        $entityManager = $this->getDoctrine()->getManager();
        $auction
            ->setExpiresAt(new \DateTime())
            ->setStatus(Auction::STATUS_FINISHED);

        $entityManager = $this->getDoctrine()->getManager();//pobiera menadzera
        $entityManager->persist($auction);//ma zapisac do bazy
        $entityManager->flush();//fizyznie zapisuje do bazy

        $this->get("event_dispatcher")->dispatch(Events::AUCTION_FINISH, new AuctionEvent($auction));

        $this->addFlash("success", "Aukcja {$auction->getTitle()} została zakończona.");

        return $this->redirectToRoute("my_auction_details", ["id" => $auction->getId()]);

    }

}