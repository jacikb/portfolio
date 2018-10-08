<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Jacik2
 * Date: 30.09.18
 * Time: 12:23
 * To change this template use File | Settings | File Templates.
 */

namespace AppBundle\EventDispatcher;


use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AuctionSubscriber implements EventSubscriberInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            Events::AUCTION_ADD => "log",
            Events::AUCTION_EXPIRE => "logExpire"
        ];
    }

    /**
     * @param AuctionEvent $event
     */
    public function log(AuctionEvent $event)
    {
        $auction = $event->getAuction();
        $this->logger->info("Aukcja {$auction->getId()} została dodana.");
    }

    /**
     * @param AuctionEvent $event
     */
    public function logExpire(AuctionEvent $event)
    {
        $auction = $event->getAuction();
        $this->logger->info("Aukcja {$auction->getId()} wygasła autyomatycznie.");
    }
}