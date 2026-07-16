<?php

namespace App\Repository;

use App\Entity\Planning;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Planning>
 */
class PlanningRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Planning::class);
    }

    public function findByUserAndWeek(\App\Entity\User $user, DateTime $date)
    {
        $jourSemaine = (int)$date->format('N');
        $debutSemaine = (clone $date)->modify('-' .($jourSemaine - 1) . ' days');
        $debutSemaine ->setTime(0, 0, 0);

        $qb = $this->createQueryBuilder('p')
         ->where('p.user = :user')
         ->andWhere('p.dateDebutSemaine = :lundi')
         ->setParameter('lundi', $debutSemaine, \Doctrine\DBAL\Types\Types::DATE_MUTABLE)
         ->setParameter('user', $user->getId()->toBinary(), \Doctrine\DBAL\Types\Types::BINARY);
        return $qb->getQuery()->getOneOrNullResult();
    }
}
