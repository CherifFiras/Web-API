<?php

namespace MainBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Categorie
 *
 * @ORM\Table(name="categorie")
 * @ORM\Entity(repositoryClass="MainBundle\Repository\CategorieRepository")
 */
class Categorie
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=255)
     */
    private $libelle;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var \dateTime
     *
     * @ORM\Column(name="date_ajout", type="datetime")
     */
    private $date_ajout;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string")
     */
    private $image;

    /**
     * @var integer
     *
     * @ORM\Column(name="nombrePost", type="integer")
     */
    private $nombrePost;

    /**
     * @return mixed
     */
    public function getNombrePost()
    {
        return $this->nombrePost;
    }

    /**
     * @param mixed $nombrePost
     */
    public function setNombrePost($nombrePost)
    {
        $this->nombrePost = $nombrePost;
    }



    /**
     * @ORM\OneToMany(targetEntity="Post", mappedBy="categorie",orphanRemoval=true,fetch="EAGER")
     */
    private $post;


    /**
     * Categorie constructor.
     */
    public function __construct()
    {
        $this->post = new ArrayCollection();
        $this->date = new \DateTime();

    }

    /**
     * @return \dateTime
     */
    public function getDateAjout()
    {
        return $this->date_ajout;
    }

    /**
     * @param \dateTime $date_ajout
     */
    public function setDateAjout($date_ajout)
    {
        $this->date_ajout = $date_ajout;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return mixed
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @param mixed $post
     */
    public function setPost($post)
    {
        $this->post = $post;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set libelle
     *
     * @param string $libelle
     *
     * @return Categorie
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Categorie
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }


}

