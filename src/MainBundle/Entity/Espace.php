<?php

namespace MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Espace
 *
 * @ORM\Table(name="espace")
 * @ORM\Entity(repositoryClass="MainBundle\Repository\EspaceRepository")
 */
class Espace
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
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255)
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=255)
     */
    private $photo;


    private $file;

    /**
     * @var integer
     *
     * @ORM\Column(name="etat", type="integer")
     */
    private $etat;
    /**
     * @var integer
     *
     * @ORM\Column(name="nbrating", type="integer")
     */
    private $nbrating;
    /**
     * @var integer
     *
     * @ORM\Column(name="rating", type="integer")
     */
    private $rating;
    /**
     * @ORM\ManyToOne(targetEntity="MainBundle\Entity\User",cascade={"persist"})
     * @ORM\JoinColumn(name="IdUser",referencedColumnName="id")
     *
     */
    private $user;
    /**
     * @ORM\OneToMany(targetEntity="MainBundle\Entity\Commentaire_espace",mappedBy="espace",orphanRemoval=true,fetch="EAGER")
     */
    private $Commentaire_espace;
    /**
     * @ORM\OneToMany(targetEntity="MainBundle\Entity\Avis_espace",mappedBy="espace",orphanRemoval=true,fetch="EAGER")
     */
    private $Avis_espace;


    /**
     * @ORM\OneToMany(targetEntity="MainBundle\Entity\Photo_espace",mappedBy="espace",orphanRemoval=true,fetch="EAGER")
     */
    private $Photo_espace;

    /**
     * @return mixed
     */
    public function getPhotoEspace()
    {
        return $this->Photo_espace;
    }

    /**
     * @param mixed $Photo_espace
     */
    public function setPhotoEspace($Photo_espace)
    {
        $this->Photo_espace = $Photo_espace;
    }

    /**
     * @return mixed
     */
    public function getCommentaireEspace()
    {
        return $this->Commentaire_espace;
    }

    /**
     * @param mixed $Commentaire_espace
     */
    public function setCommentaireEspace($Commentaire_espace)
    {
        $this->Commentaire_espace = $Commentaire_espace;
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
     * Set titre
     *
     * @param string $titre
     *
     * @return Espace
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Espace
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


    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return Espace
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set photo
     *
     * @param string $photo
     *
     * @return Espace
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }



    /**
     * Set etat
     *
     * @param integer $etat
     *
     * @return Espace
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat
     *
     * @return integer
     */
    public function getEtat()
    {
        return $this->etat;
    }
    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     */
    public function setFile(UploadedFile $file)
    {
        $this->file = $file;
    }

    public function getUploadDir()
    {
        return 'uploads/images';
    }

    public function getAbsolutRoot()
    {
        return $this->getUploadRoot().$this->photo;
    }

    public function getWebPath()
    {
        return $this->getUploadDir().'/'.$this->photo;
    }

    public function getUploadRoot()
    {
        return __DIR__.'/../../../web/'.$this->getUploadDir().'/';
    }
    public function upload()
    {
        if($this->file === null){
            return ;}
        $this->photo= $this->file->getClientOriginalName();

        if(!is_dir($this->getUploadRoot()))
        {
            mkdir($this->getUploadRoot(),'0777',true);
        }
        $this->file->move($this->getUploadRoot(),$this->photo);
        unset($this->file);
    }
    /**
     * Set user
     *
     * @param User $user
     *
     * @return Espace
     */
    public function setEspace(Espace $user = null)
    {
        $this->user = $user;

        return $this;
    }


    /**
     * Set rating
     *
     * @param integer $rating
     *
     * @return Espace
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return integer
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set nbrating
     *
     * @param integer $nbrating
     *
     * @return Espace
     */
    public function setNbrating($nbrating)
    {
        $this->nbrating = $nbrating;

        return $this;
    }

    /**
     * Get nbrating
     *
     * @return integer
     */
    public function getNbrating()
    {
        return $this->nbrating;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->Commentaire_espace = new \Doctrine\Common\Collections\ArrayCollection();
        $this->Photo_espace = new \Doctrine\Common\Collections\ArrayCollection();
        $this->Avis_espace = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set user
     *
     * @param \MainBundle\Entity\User $user
     *
     * @return Espace
     */
    public function setUser(\MainBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \MainBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add commentaireEspace
     *
     * @param \MainBundle\Entity\Commentaire_espace $commentaireEspace
     *
     * @return Espace
     */
    public function addCommentaireEspace(\MainBundle\Entity\Commentaire_espace $commentaireEspace)
    {
        $this->Commentaire_espace[] = $commentaireEspace;

        return $this;
    }

    /**
     * Remove commentaireEspace
     *
     * @param \MainBundle\Entity\Commentaire_espace $commentaireEspace
     */
    public function removeCommentaireEspace(\MainBundle\Entity\Commentaire_espace $commentaireEspace)
    {
        $this->Commentaire_espace->removeElement($commentaireEspace);
    }

    /**
     * Add photoEspace
     *
     * @param \MainBundle\Entity\Photo_espace $photoEspace
     *
     * @return Espace
     */
    public function addPhotoEspace(\MainBundle\Entity\Photo_espace $photoEspace)
    {
        $this->Photo_espace[] = $photoEspace;

        return $this;
    }

    /**
     * Remove photoEspace
     *
     * @param \MainBundle\Entity\Photo_espace $photoEspace
     */
    public function removePhotoEspace(\MainBundle\Entity\Photo_espace $photoEspace)
    {
        $this->Photo_espace->removeElement($photoEspace);
    }
}
