<?php


namespace MainBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * jaime
 *
 * @ORM\Table(name="jaime")
 * @ORM\Entity(repositoryClass="MainBundle\Repository\jaimeRepository")
 */
class jaime
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
     * @ORM\ManyToOne(targetEntity="MainBundle\Entity\User" ,cascade={"persist"})
     * @ORM\JoinColumn(name="IdUser",referencedColumnName="id")
     *
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="MainBundle\Entity\Post" , cascade={"persist"})
     * @ORM\JoinColumn(name="IdPost",referencedColumnName="id")
     *
     */
    private $post;




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
     * @return mixed
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * @param mixed $commentaire
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;
    }


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
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




}