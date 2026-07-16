<?php

namespace App\Repository;

use App\Entity\Absence;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Absence>
 */
class AbsenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Absence::class);
    }


    // Cherche une absence pour un agent à une date précise
    public function findAbsenceForUserOnDate(\App\Entity\User $user, DateTime $date)
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.user = :user')
            ->andWhere('p.dateDebut <= :date')
            ->andWhere('p.dateFin >= :date')
            ->setParameter('user', $user->getId()->toBinary(), \Doctrine\DBAL\Types\Types::BINARY)
            ->setParameter('date', $date, \Doctrine\DBAL\Types\Types::DATE_MUTABLE)
            ->setMaxResults(1);
        return $qb->getQuery()->getOneOrNullResult();
    }
}
