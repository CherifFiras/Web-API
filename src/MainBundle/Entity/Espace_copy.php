<?php

namespace MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Espace_copy
 *
 * @ORM\Table(name="espace_copy")
 * @ORM\Entity(repositoryClass="MainBundle\Repository\Espace_copyRepository")
 */
class Espace_copy
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
     * @ORM\Column(name="description", type="text"))
     */
    private $description;
    /**
 * @var string
 *
 * @ORM\Column(name="nom", type="string", length=255)
 */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     */
    private $prenom;
    /**
     * @var integer
     *
     * @ORM\Column(name="id_esp", type="integer")
     */
    private $id_esp;


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
     * @return Espace_copy
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
     * @return Espace_copy
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
     * Set idEsp
     *
     * @param integer $idEsp
     *
     * @return Espace_copy
     */
    public function setIdEsp($idEsp)
    {
        $this->id_esp = $idEsp;

        return $this;
    }

    /**
     * Get idEsp
     *
     * @return integer
     */
    public function getIdEsp()
    {
        return $this->id_esp;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Espace_copy
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
     * @return Espace_copy
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
}
