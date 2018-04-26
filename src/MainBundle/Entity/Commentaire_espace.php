<?php

namespace MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commentaire_espace
 *
 * @ORM\Table(name="commentaire_espace")
 * @ORM\Entity(repositoryClass="MainBundle\Repository\Commentaire_espaceRepository")
 */
class Commentaire_espace
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
     * @ORM\Column(name="dateCommentaire", type="datetime")
     */
    private $dateCommentaire;

    /**
     * @ORM\ManyToOne(targetEntity="MainBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="IdUser",referencedColumnName="id")
     *
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="MainBundle\Entity\Espace",cascade={"persist"})
     * @ORM\JoinColumn(name="IdEspace",referencedColumnName="id")
     *
     */
    protected $espace;
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
     * @return Commentaire_espace
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
     * @return Commentaire_espace
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
     * @return mixed
     */
    public function getEspace()
    {
        return $this->espace;
    }

    /**
     * Set espace
     *
     * @param \MainBundle\Entity\Espace $espace
     *
     * @return Commentaire_espace
     */
    public function setEspace(\MainBundle\Entity\Espace $espace = null)
    {
        $this->espace = $espace;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set user
     *
     * @param \MainBundle\Entity\User $user
     *
     * @return Commentaire_espace
     */
    public function setUser(\MainBundle\Entity\User $user = null)
    {
        $this->user = $user;
    }
}
