<?php

namespace MainBundle\Repository;

/**
 * Avis_espaceRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class Avis_espaceRepository extends \Doctrine\ORM\EntityRepository
{
    public function findrati($id){
        $q=$this->getEntityManager()
            ->createQuery("select c from MainBundle:Avis_espace c WHERE c.espace=:ide")
            ->setParameter(":ide",$id);

        return $q->getResult();
    }
}