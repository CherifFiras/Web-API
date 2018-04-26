<?php
// src/AppBundle/Entity/User.php

namespace MainBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Mgilet\NotificationBundle\Annotation\Notifiable;
use Mgilet\NotificationBundle\NotifiableInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="MainBundle\Repository\UserRepository")
 * @ORM\Table(name="user")
 * @Notifiable(name="user")
 * @Vich\Uploadable
 */
class User extends BaseUser implements NotifiableInterface
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
        $this->interets = new ArrayCollection();
        $this->receivedDemandes = new ArrayCollection();
        $this->sendedDemandes = new ArrayCollection();
    }

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    protected $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     */
    protected $prenom;

    /**
     * @var Date
     *
     * @ORM\Column(name="date_naissance", type="date")
     */
    protected $date_naissance;

    /**
     * @var string
     *
     * @ORM\Column(name="genre", type="string" , length=1)
     */
    protected $genre;

    /**
     * @var string
     *
     * @ORM\Column(name="pays", type="string" , length=255,nullable=true)
     */
    protected $pays;

    /**
     * @var string
     *
     * @ORM\Column(name="region", type="string" , length=255,nullable=true)
     */
    protected $region;

    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string" , length=255,nullable=true)
     */
    protected $ville;

    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string" , length=255,nullable=true)
     */
    protected $tel;

    /**
     * @var string
     *
     * @ORM\Column(name="place_naiss", type="string" , length=255,nullable=true)
     */
    protected $place_naiss;

    /**
     * @var string
     *
     * @ORM\Column(name="religion", type="string" , length=255,nullable=true)
     */
    protected $relegion;

    /**
     * @var string
     *
     * @ORM\Column(name="apropos", type="string" , length=255,nullable=true)
     */
    protected $apropos;

    /**
     * @var string
     *
     * @ORM\Column(name="facebook", type="string" , length=255,nullable=true)
     */
    protected $facebook;
    /**
     * @var string
     *
     * @ORM\Column(name="twitter", type="string" , length=255,nullable=true)
     */
    protected $twitter;
    /**
     * @var string
     *
     * @ORM\Column(name="instagram", type="string" , length=255,nullable=true)
     */
    protected $instagram;


    /**
     * @ORM\Column(name="image",type="string", length=255,nullable=true)
     * @var string
     */
    private $image="unknownphoto.jpg";


    /**
     * @Vich\UploadableField(mapping="profil_images", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     * @var \DateTime
     */
    private $updatedAt;


    /**
     * @return string
     */
    public function getOccupation()
    {
        return $this->occupation;
    }

    /**
     * @param string $occupation
     */
    public function setOccupation($occupation)
    {
        $this->occupation = $occupation;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="occupation", type="string" , length=255,nullable=true)
     */
    public $occupation;

    /**
     * @ORM\OneToMany(targetEntity="MainBundle\Entity\CentreInteret", mappedBy="user")
     */
    private $interets;

    /**
     * @ORM\OneToMany(targetEntity="MainBundle\Entity\Relation", mappedBy="acceptor")
     */
    private $acceptors;


    /**
     * @ORM\OneToMany(targetEntity="MainBundle\Entity\Relation", mappedBy="requester")
     */
    private $requesters;

    /**
     * @ORM\OneToMany(targetEntity="MainBundle\Entity\Demande", mappedBy="sender")
     */
    private $sendedDemandes;


    /**
     * @ORM\OneToMany(targetEntity="MainBundle\Entity\Demande", mappedBy="receiver")
     */
    private $receivedDemandes;

    /**
     * @return mixed
     */
    public function getReceivedDemandes()
    {
        return $this->receivedDemandes;
    }

    /**
     * @param mixed $receivedDemandes
     */
    public function setReceivedDemandes($receivedDemandes)
    {
        $this->receivedDemandes = $receivedDemandes;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return mixed
     */
    public function getSendedDemandes()
    {
        return $this->sendedDemandes;
    }

    /**
     * @param mixed $sendedDemandes
     */
    public function setSendedDemandes($sendedDemandes)
    {
        $this->sendedDemandes = $sendedDemandes;
    }



    /**
     * @return string
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * @param string $facebook
     */
    public function setFacebook($facebook)
    {
        $this->facebook = $facebook;
    }

    /**
     * @return string
     */
    public function getInstagram()
    {
        return $this->instagram;
    }

    /**
     * @param string $instagram
     */
    public function setInstagram($instagram)
    {
        $this->instagram = $instagram;
    }

    /**
     * @return string
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * @param string $twitter
     */
    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getApropos()
    {
        return $this->apropos;
    }

    /**
     * @param string $apropos
     */
    public function setApropos($apropos)
    {
        $this->apropos = $apropos;
    }

    /**
     * @return string
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * @param string $tel
     */
    public function setTel($tel)
    {
        $this->tel = $tel;
    }

    /**
     * @return string
     */
    public function getPlaceNaiss()
    {
        return $this->place_naiss;
    }

    /**
     * @param string $place_naiss
     */
    public function setPlaceNaiss($place_naiss)
    {
        $this->place_naiss = $place_naiss;
    }

    /**
     * @return string
     */
    public function getRelegion()
    {
        return $this->relegion;
    }

    /**
     * @param string $relegion
     */
    public function setRelegion($relegion)
    {
        $this->relegion = $relegion;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getPays()
    {
        return $this->pays;
    }

    /**
     * @param string $pays
     */
    public function setPays($pays)
    {
        $this->pays = $pays;
    }

    /**
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param string $region
     */
    public function setRegion($region)
    {
        $this->region = $region;
    }

    /**
     * @return string
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * @param string $ville
     */
    public function setVille($ville)
    {
        $this->ville = $ville;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return User
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return User
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set dateNaissance
     *
     * @param \DateTime $dateNaissance
     *
     * @return User
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->date_naissance = $dateNaissance;

        return $this;
    }

    /**
     * Get dateNaissance
     *
     * @return \DateTime
     */
    public function getDateNaissance()
    {
        return $this->date_naissance;
    }

    /**
     * Set genre
     *
     * @param string $genre
     *
     * @return User
     */
    public function setGenre($genre)
    {
        $this->genre = $genre;

        return $this;
    }

    /**
     * Get genre
     *
     * @return string
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * @return mixed
     */
    public function getInterets()
    {
        return $this->interets;
    }

    /**
     * @param mixed $interets
     */
    public function setInterets($interets)
    {
        $this->interets = $interets;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     */
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        if ($image) {
            $this->updatedAt = new \DateTime('now');
        }
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

    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @return mixed
     */
    public function getAcceptors()
    {
        return $this->acceptors;
    }

    /**
     * @param mixed $acceptors
     */
    public function setAcceptors($acceptors)
    {
        $this->acceptors = $acceptors;
    }

    /**
     * @return mixed
     */
    public function getRequesters()
    {
        return $this->requesters;
    }

    /**
     * @param mixed $requesters
     */
    public function setRequesters($requesters)
    {
        $this->requesters = $requesters;
    }
}
