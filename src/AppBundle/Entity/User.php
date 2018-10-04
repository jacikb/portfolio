<?php

namespace AppBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Auction[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="Auction", mappedBy="owner")
     * @orm\JoinColumn(name="owner_id", referencedColumnName="id")
     */
    private $auctions;

    /**
     * @var Offer[]|ArrayCollection
     * #jeden user ma wiel ofert
     * @ORM\OneToMany(targetEntity="Offer", mappedBy="owner")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
     */
    private $offers;


    /**
     * @var Article[]|ArrayCollection
     * #jeden user ma wiel ofert
     * @ORM\OneToMany(targetEntity="Article", mappedBy="owner")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
     */
    private $articles;

 

    /**
     * User constructor
     */
    public function __construct()
    {
        //kontruktor już jest w klasie BaseUser dlatego najpierw on
        parent::__construct();
        $this->auctions = new ArrayCollection();
        $this->offers = new ArrayCollection();
    }

    /**
     * @return Auction[]|ArrayCollection
     */
    public function getAuctions()
    {
        return $this->auctions;
    }

    /**
     * @param Auction $auction
     *
     * @return $this
     */
    public function addAuctions(Auction $auction)
    {
        $this->auctions[] = $auction;
        return $this;
    }

    /**
     * @return Offer[]|ArrayCollection
     */
    public function getOffers()
    {
        return $this->offers;
    }

    /**
     * @param Offer $offer
     *
     * @return $this
     */
    public function addOffers(Offer $offer)
    {
        $this->offers[] = $offer;
        return $this;
    }

    /**
     * @return Article[]|ArrayCollection
     */
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * @param Article $article
     *
     * @return $this
     */
    public function addArticles(Article $article)
    {
        $this->articles[] = $article;
        return $this;
    }

}