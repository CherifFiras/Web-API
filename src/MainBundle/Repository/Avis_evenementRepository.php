<?php

namespace MainBundle\Repository;

/**
 * Avis_evenementRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class Avis_evenementRepository extends \Doctrine\ORM\EntityRepository
{
    public function findbyid($id){
        $q=$this->getEntityManager()
            ->createQuery("select m from MainBundle:Avis_evenement m WHERE m.evenement=:id ")
            ->setParameter(":id",$id);
        return $q->getResult();
    }
}
