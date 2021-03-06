<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Text
 *
 * @ORM\Table(name="article_item")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArticleItemRepository")
 */
class ArticleItem
{
    const STATUS_PUBLIC = "PUB";
    const STATUS_PRIVARE = "PRV";
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Article", inversedBy="articleItems")
     * @ORM\JoinColumn(name="article_id", referencedColumnName="id")
     */
    private $article;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=3)
     * @Assert\NotBlank(message = "Status musi być wybrany")
     * @Assert\Length(
     * min=3,
     * max=3,
     * minMessage="Status miso mieć 3 znaki!",
     * maxMessage="Status miso mieć 3 znaki!"
     * )
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=150)
     * @Assert\NotBlank(message = "Tytuł nie może być pusty")
     * @Assert\Length(
     *      min=3,
     *      max=150,
     *      minMessage="Tytuł nie może byc krótszy niż 5 znaków!",
     *      maxMessage="Tytuł nie może być dłuższy niż 100 znaków!"
     * )
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     * @Assert\NotBlank(message = "Treść nie może byc pusta")
     * @Assert\Length(
     *      min=5,
     *      minMessage="Treść musi mieć przynajmiej 5 znakó!",
     * )
     */
    private $content;

    /**
     * @var int
     *
     * @ORM\Column(name="sort", type="integer", nullable=true)
     */
    private $sort;



    /**
    * doc https://symfony.com/doc/3.4/controller/upload_file.html
    * @ORM\Column(name="photo", type="string", nullable=true)
    * @Assert\File(maxSize="10000000")
    * @Assert\File(mimeTypes={ "application/pdf", "image/png", "image/jpeg" })
    */
    private $photo;





    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createAt", type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $createAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    private $updatedAt;


    /**
     * User constructor
     */
    public function __construct()
    {
        //parent::__construct();
        $this->sort = 10;
    }


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set article.
     *
     * @param int $article
     *
     * @return ArticleItem
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }





    /**
     * Set article
     *
     * @param integer $owner
     *
     * @return ArticleItem
     */
    public function setArticle($article)
    {
        $this->article = $article;

        return $this;
    }

    /**
     * Get article
     *
     * @return int
     */
    public function getArticle()
    {
        return $this->article;
    }



    /**
     * Set status.
     *
     * @param string $status
     *
     * @return ArticleItem
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return ArticleItem
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content.
     *
     * @param string $content
     *
     * @return ArticleItem
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set sort.
     *
     * @param int $sort
     *
     * @return ArticleItem
     */
    public function setSort($sort)
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * Get sort.
     *
     * @return int
     */
    public function getSort()
    {
        return $this->sort;
    }


    /**
     * @param $photo
     * @return ArticleItem
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
        return $this;
    }

    /**
     * Get photo1
     * @return mixed
     */
    public function getPhoto()
    {
        return $this->photo;
    }







    /**
     * Set createAt
     *
     * @param \DateTime $createAt
     *
     * @return Article
     */
    public function setCreateAt($createAt)
    {
        $this->createAt = $createAt;

        return $this;
    }

    /**
     * Get createAt
     *
     * @return \DateTime
     */
    public function getCreateAt()
    {
        return $this->createAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return ArticleItem
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }


}
