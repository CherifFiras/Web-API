<?php

namespace MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Avis_espace
 *
 * @ORM\Table(name="avis_espace")
 * @ORM\Entity(repositoryClass="MainBundle\Repository\Avis_espaceRepository")
 */
class Avis_espace
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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @ORM\ManyToOne(targetEntity="MainBundle\Entity\Espace")
     * @ORM\JoinColumn(name="IdEspace",referencedColumnName="id")
     *
     */
    private $espace;


    /**
     * @ORM\ManyToOne(targetEntity="MainBundle\Entity\User")
     * @ORM\JoinColumn(name="IdUser",referencedColumnName="id")
     *
     */
    private $user;

    /**
     * @return mixed
     */
    public function getEspace()
    {
        return $this->espace;
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
     * @return Avis_espace
     */
    public function setUser(\MainBundle\Entity\User $user = null)
    {
        $this->user = $user;
    }


    /**
     * Set espace
     *
     * @param \MainBundle\Entity\Espace $espace
     *
     * @return Avis_espace
     */
    public function setEspace(\MainBundle\Entity\Espace $espace = null)
    {
        $this->espace = $espace;
    }

    /**
     * Set nbrating
     *
     * @param integer $nbrating
     *
     * @return Avis_espace
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
     * Set rating
     *
     * @param integer $rating
     *
     * @return Avis_espace
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
}
