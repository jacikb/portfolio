<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Jacik2
 * Date: 16.09.18
 * Time: 22:29
 * To change this template use File | Settings | File Templates.
 */

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Auction;
use AppBundle\Entity\Offer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Form\BidType;

class OfferController extends Controller
{
    /**
     * @Route("/auction/buy/{id}", name="offer_buy", methods={"POST"})
     * @param Auction $auction
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function buyAction(Auction $auction)
    {
        $this->denyAccessUnlessGranted("ROLE_USER");

        $offer = new Offer();
        $offer
            ->setAuction($auction)
            ->setType(Offer::TYPE_BUY)
            ->setPrice($auction->getPrice());
            //->setCreatedAt($auction->getCreateedAt())
        $auction
            ->setStatus(Auction::STATUS_FINISHED)
            ->setExpiresAt(new \DateTime());

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($offer);
        $entityManager->persist($auction);
        $entityManager->flush();

        $this->addFlash("success","Kupiłeś przedmiot {$auction->getTitle()} za kwotę {$offer->getPrice()} zł");

        return $this->redirectToRoute("auction_details", ["id" => $auction->getId()]);
    }

    /**
     * @Route("/auction/bid/{id}", name="offer_bid", methods={"POST"})
     * @param Request $request
     * @param Auction $auction
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function bidAction(Request $request, Auction $auction)
    {
        $this->denyAccessUnlessGranted("ROLE_USER");

        $offer = new Offer();

        $bidForm = $this->createForm(BidType::class, $offer);
        $bidForm->handleRequest($request);
        if($bidForm->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            //1 parametr auction - po niej szukamy
            //2 parametra sortowanie
            $lastOffer = $entityManager
                ->getRepository(Offer::class)
                ->findOneBy(["auction" => $auction], ["createdAt" => "DESC"]);

            if(isset($lastOffer)){
                if($offer->getPrice() <= $lastOffer->getPrice()){
                    $this->addFlash("error", "Podana cena jest nie może być niższa niż {$lastOffer->getPrice()} zł!");
                    return $this->redirectToRoute("auction_details",["id" => $auction->getId()]);
                }
            } else {
                if($offer->getPrice() < $auction->getStartingPrice()){
                    $this->addFlash("error", "Twoja oferta nie może byc niższa oc ceny wywoławczej");
                    return $this->redirectToRoute("auction_details",["id" => $auction->getId()]);
                }
            }

            $offer
                ->setType(Offer::TYPE_BID)
                ->setAuction($auction);


            $entityManager->persist($offer);
            $entityManager->flush();

            $this->addFlash(
                "success",
                "Złożyłeś ofertę na przedmiot {$auction->getTitle()} za kwotę {$offer->getPrice()} zł"
            );
        } else {
            $this->addFlash("error","Nie udało się zalicytować przedmiotu {$auction->getTitle()}");
        }
        return $this->redirectToRoute("auction_details",["id" => $auction->getId()]);
    }



}





