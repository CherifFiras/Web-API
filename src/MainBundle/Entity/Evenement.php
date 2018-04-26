<?php

namespace MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Evenement
 *
 * @ORM\Table(name="evenement")
 * @ORM\Entity(repositoryClass="MainBundle\Repository\EvenementRepository")
 */
class Evenement
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
     * @ORM\Column(name="image_eve",type="string", length=10000, nullable=true)
     *
     * @Assert\NotBlank(message="Please, upload the product brochure as a PDF file.")
     * @Assert\File(mimeTypes={ "image/jpeg" , "image/jpg" , "image/png" , "image/gif" })
     */
    private $imageEve;
    /**
     * @var int
     *
     * @ORM\Column(name="vue", type="integer", nullable=true )
     *
     */

    private $vue;
    /**
     * @var int
     *
     * @ORM\Column(name="nbplaces", type="integer" )
     *
     */

    private $nbplaces;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateEvenement", type="datetime", nullable=true )
     * @Assert\GreaterThan("today")
     */

    private $dateEvenement;

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
     * @ORM\Column(name="titreCordination", type="string", length=255)
     */
    private $titreCordination;

    /**
     * @ORM\OneToMany(targetEntity="MainBundle\Entity\Participation",mappedBy="evenement",orphanRemoval=true,fetch="EAGER")
     */
    private $participation;

    /**
     * @return mixed
     */
    public function getParticipation()
    {
        return $this->participation;
    }

    /**
     * @param mixed $participation
     */
    public function setParticipation($participation)
    {
        $this->participation = $participation;
    }


    /**
     * Evenement constructor.
     */
    public function __construct()
    {
        $this->setVue(0);
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
     * Set dateEvenement
     *
     * @param \DateTime $dateEvenement
     *
     * @return Evenement
     */
    public function setDateEvenement($dateEvenement)
    {
        $this->dateEvenement = $dateEvenement;

        return $this;
    }

    /**
     * Get dateEvenement
     *
     * @return \DateTime
     */
    public function getDateEvenement()
    {
        return $this->dateEvenement;
    }

    /**
     * Set titre
     *
     * @param string $titre
     *
     * @return Evenement
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
     * @return Evenement
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
     * Set titreCordination
     *
     * @param string $titreCordination
     *
     * @return Evenement
     */
    public function setTitreCordination($titreCordination)
    {
        $this->titreCordination = $titreCordination;

        return $this;
    }

    /**
     * Get titreCordination
     *
     * @return string
     */
    public function getTitreCordination()
    {
        return $this->titreCordination;
    }

    /**
     * Set imageEve
     *
     * @param string $imageEve
     *
     * @return Evenement
     */
    public function setImageEve($imageEve)
    {
        $this->imageEve = $imageEve;

        return $this;
    }

    /**
     * Get imageEve
     *
     * @return string
     */
    public function getImageEve()
    {
        return $this->imageEve;
    }

    /**
     * Set vue
     *
     * @param integer $vue
     *
     * @return Evenement
     */
    public function setVue($vue)
    {
        $this->vue = $vue;

        return $this;
    }

    /**
     * Get vue
     *
     * @return integer
     */
    public function getVue()
    {
        return $this->vue;
    }

    /**
     * Set nbplaces
     *
     * @param integer $nbplaces
     *
     * @return Evenement
     */
    public function setNbplaces($nbplaces)
    {
        $this->nbplaces = $nbplaces;

        return $this;
    }

    /**
     * Get nbplaces
     *
     * @return integer
     */
    public function getNbplaces()
    {
        return $this->nbplaces;
    }
}
