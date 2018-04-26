<?php

namespace MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Photo_espace
 *
 * @ORM\Table(name="photo_espace")
 * @ORM\Entity(repositoryClass="MainBundle\Repository\Photo_espaceRepository")
 */
class Photo_espace
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;



    private $file1;
    private $file2;
    private $file3;
    private $file4;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $photo1;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $photo2;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $photo3;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $photo4;

    /**
     * @ORM\ManyToOne(targetEntity="MainBundle\Entity\Espace",cascade={"persist"})
     * @ORM\JoinColumn(name="IdEspace",referencedColumnName="id")
     *
     */
    private $espace;
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
     * @param mixed $file1
     */
    public function setFile1(UploadedFile $file1)
    {
        $this->file1 = $file1;
    }
    /**
     * @param mixed $file2
     */
    public function setFile2(UploadedFile $file2)
    {
        $this->file2 = $file2;
    }
    /**
     * @param mixed $file3
     */
    public function setFile3(UploadedFile $file3)
    {
        $this->file3 = $file3;
    }
    /**
     * @param mixed $file1
     */
    public function setFile4(UploadedFile $file4)
    {
        $this->file4 = $file4;
    }
    /**
     * @return mixed
     */
    public function getFile1()
    {
        return $this->file1;
    }


    /**
     * @return mixed
     */
    public function getFile2()
    {
        return $this->file2;
    }


    /**
     * @return mixed
     */
    public function getFile3()
    {
        return $this->file3;
    }


    /**
     * @return mixed
     */



    public function getFile4()
    {
        return $this->file4;
    }



    public function getUploadDir()
    {
        return 'uploads/images';
    }

    public function getAbsolut1Root()
    {
        return $this->getUploadRoot().$this->photo1;
    }
    public function getAbsolut2Root()
    {
        return $this->getUploadRoot().$this->photo2;
    }
    public function getAbsolut3Root()
    {
        return $this->getUploadRoot().$this->photo3;
    }
    public function getAbsolut4Root()
    {
        return $this->getUploadRoot().$this->photo4;
    }
    public function getWeb1Path()
    {
        return $this->getUploadDir().'/'.$this->photo1;
    }
    public function getWeb2Path()
    {
        return $this->getUploadDir().'/'.$this->photo2;
    }
    public function getWeb3Path()
    {
        return $this->getUploadDir().'/'.$this->photo3;
    }
    public function getWeb4Path()
    {
        return $this->getUploadDir().'/'.$this->photo4;
    }

    public function getUploadRoot()
    {
        return __DIR__.'/../../../web/'.$this->getUploadDir().'/';
    }
    public function upload()
    {
        if($this->file1 === null){
            return ;}
        $this->photo1= $this->file1->getClientOriginalName();
        $this->photo2= $this->file2->getClientOriginalName();
        $this->photo3= $this->file3->getClientOriginalName();
        $this->photo4= $this->file4->getClientOriginalName();

        if(!is_dir($this->getUploadRoot()))
        {
            mkdir($this->getUploadRoot(),'0777',true);
        }
        $this->file1->move($this->getUploadRoot(),$this->photo1);
        $this->file2->move($this->getUploadRoot(),$this->photo2);
        $this->file3->move($this->getUploadRoot(),$this->photo3);
        $this->file4->move($this->getUploadRoot(),$this->photo4);
        unset($this->file1);
        unset($this->file2);
        unset($this->file3);
        unset($this->file4);
    }










    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }



    /**
     * Set photo1
     *
     * @param string $photo1
     *
     * @return Photo_espace
     */
    public function setPhoto1($photo1)
    {
        $this->photo1 = $photo1;

        return $this;
    }

    /**
     * Get photo1
     *
     * @return string
     */
    public function getPhoto1()
    {
        return $this->photo1;
    }

    /**
     * Set photo2
     *
     * @param string $photo2
     *
     * @return Photo_espace
     */
    public function setPhoto2($photo2)
    {
        $this->photo2 = $photo2;

        return $this;
    }

    /**
     * Get photo2
     *
     * @return string
     */
    public function getPhoto2()
    {
        return $this->photo2;
    }

    /**
     * Set photo3
     *
     * @param string $photo3
     *
     * @return Photo_espace
     */
    public function setPhoto3($photo3)
    {
        $this->photo3 = $photo3;

        return $this;
    }

    /**
     * Get photo3
     *
     * @return string
     */
    public function getPhoto3()
    {
        return $this->photo3;
    }

    /**
     * Set photo4
     *
     * @param string $photo4
     *
     * @return Photo_espace
     */
    public function setPhoto4($photo4)
    {
        $this->photo4 = $photo4;

        return $this;
    }

    /**
     * Get photo4
     *
     * @return string
     */
    public function getPhoto4()
    {
        return $this->photo4;
    }
}
