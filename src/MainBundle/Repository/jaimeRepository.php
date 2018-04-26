<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 21/02/2018
 * Time: 18:17
 */

namespace MainBundle\Repository;


class jaimeRepository  extends \Doctrine\ORM\EntityRepository
{
    public function findeverything($iduser,$id)
    {
        $q=$this->getEntityManager()->createQuery("select v from MainBundle:jaime v  where 
              v.user=:iduser and v.post=:id  ")->setParameter(":iduser",$iduser)->setParameter(":id",$id)->setMaxResults(1)
        ;
        return $q->getOneOrNullResult();
    }

    public function findeverything1($id)
    {
        $q=$this->getEntityManager()->createQuery("select v from MainBundle:jaime v  where 
              v.post=:id ")->setParameter(":id",$id)->setMaxResults(1)
        ;
        return $q->getOneOrNullResult();
    }
    public function findedql($iduser)
    {
        $q=$this->getEntityManager()->createQuery("delete from MainBundle:jaime v  where 
              v.user=:iduser ")->setParameter(":iduser",$iduser)
        ;
        return $q->getResult();
    }

    public function findjaime($id)
    {
        $q=$this->getEntityManager()->createQuery("select v from MainBundle:jaime v  where 
              v.post=:id ")->setParameter(":id",$id)
        ;
        return $q->getResult();
    }

    public function calcule_jaime($id)
    {
        $q=$this->getEntityManager()->createQuery("select v from MainBundle:jaime v  where 
              v.post=:id ")
            ->setParameter(":id",$id)
        ;
        return $q->getResult();
    }


}