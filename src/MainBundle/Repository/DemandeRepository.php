<?php

namespace MainBundle\Repository;

/**
 * DemandeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DemandeRepository extends \Doctrine\ORM\EntityRepository
{
    public function checkMembers($user)
    {
        $qb = $this->createQueryBuilder('r');
        $qb->select($qb->expr()->count("r"));
        $qb->andWhere("r.sender in (:u1)")->setParameter(":u1",$user);
        $qb->andWhere("r.receiver in (:u2)")->setParameter(":u2",$user);
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function fetchDemandes($u)
    {
        $qb = $this->createQueryBuilder('d');
        $qb->andWhere("d.receiver = :cu")->setParameter(":cu",$u);
        $qb->innerJoin("d.sender","se","WITH");
        return $qb->getQuery()->execute();
    }
}