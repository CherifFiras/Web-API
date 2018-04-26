<?php

namespace MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Avis_evenement
 *
 * @ORM\Table(name="Avis_evenement")
 * @ORM\Entity(repositoryClass="MainBundle\Repository\Avis_evenementRepository")
 */
class Avis_evenement
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
     * @ORM\Column(name="contenu", type="text")
     */
    private $contenu;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateAvis", type="datetime")
     */
    private $dateAvis;

    /**
     * @var int
     *
     * @ORM\Column(name="valeur", type="smallint")
     */
    private $valeur;

    public function __construct()
    {
        $this->dateAvis=new \DateTime();
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
     * Set contenu
     *
     * @param string $contenu
     *
     * @return Avis_evenement
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * Get contenu
     *
     * @return string
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * Set dateCommentaire
     *
     * @param \DateTime $dateCommentaire
     *
     * @return Avis_evenement
     */
    public function setDateCommentaire($dateCommentaire)
    {
        $this->dateCommentaire = $dateCommentaire;

        return $this;
    }

    /**
     * Get dateCommentaire
     *
     * @return \DateTime
     */
    public function getDateCommentaire()
    {
        return $this->dateCommentaire;
    }

    /**
     * @ORM\ManyToOne(targetEntity="MainBundle\Entity\Evenement",cascade={})
     * @ORM\JoinColumn(name="IdEvenement",referencedColumnName="id",onDelete="CASCADE")
     *
     */
    private $evenement;


    /**
     * @ORM\ManyToOne(targetEntity="MainBundle\Entity\User")
     * @ORM\JoinColumn(name="IdUser",referencedColumnName="id")
     *
     */
    private $user;

    /**
     * @return mixed
     */
    public function getEvenement()
    {
        return $this->evenement;
    }

    /**
     * @param mixed $evenement
     */
    public function setEvenement($evenement)
    {
        $this->evenement = $evenement;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * Set valeur
     *
     * @param integer $valeur
     *
     * @return Avis_evenement
     */
    public function setValeur($valeur)
    {
        $this->valeur = $valeur;

        return $this;
    }

    /**
     * Get valeur
     *
     * @return integer
     */
    public function getValeur()
    {
        return $this->valeur;
    }

    /**
     * Set valeurCommentaire
     *
     * @param integer $valeurCommentaire
     *
     * @return Avis_evenement
     */
    public function setValeurCommentaire($valeurCommentaire)
    {
        $this->valeurCommentaire = $valeurCommentaire;

        return $this;
    }

    /**
     * Get valeurCommentaire
     *
     * @return integer
     */
    public function getValeurCommentaire()
    {
        return $this->valeurCommentaire;
    }

    /**
     * Set dateAvis
     *
     * @param \DateTime $dateAvis
     *
     * @return Avis_evenement
     */
    public function setDateAvis($dateAvis)
    {
        $this->dateAvis = $dateAvis;

        return $this;
    }

    /**
     * Get dateAvis
     *
     * @return \DateTime
     */
    public function getDateAvis()
    {
        return $this->dateAvis;
    }
}
