<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Jacik2
 * Date: 30.09.18
 * Time: 12:11
 * To change this template use File | Settings | File Templates.
 */

namespace AppBundle\EventDispatcher;


use Symfony\Component\EventDispatcher\Event;
use AppBundle\Entity\Auction;


class AuctionEvent extends Event
{
    /**
     * @var Auction
     */
    private $auction;

    /**
     * @param Auction $auction
     */
    public function __construct(Auction $auction)
    {
        $this->auction = $auction;
    }

    /**
     * @return Auction
     */
    public function getAuction()
    {
        return $this->auction;
    }

}