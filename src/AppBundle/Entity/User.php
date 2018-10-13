<?php

namespace AppBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


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
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=40, nullable=false)
     * @Assert\NotBlank(message = "Imię nie może byc puste", groups={"Registration", "Profile"})
     * @Assert\Length(
     *      min=3,
     *      minMessage="Imię musi mieć przynajmniej 3 znaki!",
     *      max=20,
     *      maxMessage="Imię nie może być dłuższe niż 40 znaków!",
     *      groups={"Registration", "Profile"}
     * )
     */
    private $first_name;


    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=120, nullable=false)
     * @Assert\NotBlank(message = "Nazwisko nie może byc puste")
     * @Assert\Length(
     *      min=3,
     *      minMessage="Nazwisko musi mieć przynajmniej 3 znaki!",
     *      max=120,
     *     maxMessage="Nazwisko nie może być dłuższe niż 120 znaków!",
     * )
     */
    private $last_name;

    /**
     * @var Article[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="Article", mappedBy="owner")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
     */
    private $articles;



    /**
     * User constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->articles = new ArrayCollection();
    }



    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return this
     */
    public function setFirstName($firstName)
    {
        $this->first_name = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }




    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return this
     */
    public function setLastName($lastName)
    {
        $this->last_name = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
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