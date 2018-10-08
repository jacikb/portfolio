<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Auction;//tabela
use AppBundle\Form\BidType;
use AppBundle\Service\DateService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;



class AuctionController extends Controller
{

    /**
     * @Route("/ind", name="auction_index")
     * @param DateService $dateService
     * @return Response
     */
    public function indexAction(DateService $dateService)
    {
        //public function indexAction(DateService $dateService)
        // lub $dateService = $this->get(DateService::class); + zmiana w service.yml


        $entityManager = $this->getDoctrine()->getManager();
        //$auctions = $entityManager->getRepository(Auction::class)->findAll();//pobira wszystkie
        //$auctions = $entityManager->getRepository(Auction::class)->findBy(["status" => Auction::STATUS_ACTIVE]);//pobira wszystkie
        $auctions = $entityManager->getRepository(Auction::class)->findActivOrdered();//ZDEFINIOWANE W RESITORY
        $logger = $this->get("logger");//pobiuera logger z kontenera
        #logger->info("uzytkownik wszedł na Auction index");
        $logger->info("Aktualny dzień mieesiąca: " . $dateService->getDay(new \DateTime()));

        return $this->render("Auction/index.html.twig", ["auctions" => $auctions]);

    }

    /**
     * @Route("/auction/details/{id}", name="auction_details")
     *
     * @param Auction $auction
     *
     * @return  Response
     */
    public function detailsAction(Auction $auction)
    {
        if($auction->getStatus() === Auction::STATUS_FINISHED)
        {
            return $this->render("Auction/finished.html.twig",["auction" => $auction]);
        }

        //$entityManager = $this->getDoctrine()->getManager();
        //$auction = $entityManager->getRepository(Auction::class)->findOneBy(["id" => $id]);


        $buyForm = $this->createFormBuilder()
            ->setAction($this->generateUrl("offer_buy", ["id" => $auction->getId()]))
            ->setMethod(Request::METHOD_POST)
            ->add("submit", SubmitType::class, ["label" => "Kup"])
            ->getForm();

        //zrobilismy formularz sami ( klasa BidForm dlatgo createFoirm
        $bidForm = $this->createForm(
            BidType::class,
            null,
            ["action" => $this->generateUrl("offer_bid", ["id" => $auction->getId()])]
        );

        return $this->render(
            "Auction/details.html.twig", [
                "auction" => $auction,
                "buyForm" => $buyForm->createView(),
                "bidForm" => $bidForm->createView(),
            ]
        );
    }






}
