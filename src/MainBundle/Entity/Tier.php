<?php

namespace MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tier
 *
 * @ORM\Table(name="tier")
 * @ORM\Entity(repositoryClass="MainBundle\Repository\TierRepository")
 */
class Tier
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
     * @ORM\Column(name="cin", type="string", length=10)
     */
    private $cin;

    /**
     * @var string
     *
     * @ORM\Column(name="numTel", type="string", length=50)
     */
    private $numTel;

    /**
     * @var string
     *
     * @ORM\Column(name="adresseLocal", type="string", length=255)
     */
    private $adresseLocal;

    /**
     * @var string
     *
     * @ORM\Column(name="numFax", type="string", length=50, nullable=true)
     */
    private $numFax;

    /**
     * @var string
     *
     * @ORM\Column(name="numTel2", type="string", length=50, nullable=true)
     */
    private $numTel2;

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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set cin
     *
     * @param string $cin
     *
     * @return Tier
     */
    public function setCin($cin)
    {
        $this->cin = $cin;

        return $this;
    }

    /**
     * Get cin
     *
     * @return string
     */
    public function getCin()
    {
        return $this->cin;
    }

    /**
     * Set numTel
     *
     * @param string $numTel
     *
     * @return Tier
     */
    public function setNumTel($numTel)
    {
        $this->numTel = $numTel;

        return $this;
    }

    /**
     * Get numTel
     *
     * @return string
     */
    public function getNumTel()
    {
        return $this->numTel;
    }

    /**
     * Set adresseLocal
     *
     * @param string $adresseLocal
     *
     * @return Tier
     */
    public function setAdresseLocal($adresseLocal)
    {
        $this->adresseLocal = $adresseLocal;

        return $this;
    }

    /**
     * Get adresseLocal
     *
     * @return string
     */
    public function getAdresseLocal()
    {
        return $this->adresseLocal;
    }

    /**
     * Set numFax
     *
     * @param string $numFax
     *
     * @return Tier
     */
    public function setNumFax($numFax)
    {
        $this->numFax = $numFax;

        return $this;
    }

    /**
     * Get numFax
     *
     * @return string
     */
    public function getNumFax()
    {
        return $this->numFax;
    }

    /**
     * Set numTel2
     *
     * @param string $numTel2
     *
     * @return Tier
     */
    public function setNumTel2($numTel2)
    {
        $this->numTel2 = $numTel2;

        return $this;
    }

    /**
     * Get numTel2
     *
     * @return string
     */
    public function getNumTel2()
    {
        return $this->numTel2;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Tier
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
     * @return Tier
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

