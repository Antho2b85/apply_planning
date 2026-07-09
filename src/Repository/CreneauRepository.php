<?php

namespace App\Repository;

use App\Entity\Creneau;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Creneau>
 */
class CreneauRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Creneau::class);
    }

    public function getTotalMinutesForUserOnDate(\App\Entity\User $user, \DateTimeInterface $date): int
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
        SELECT SUM(c.duree)
        FROM creneau c
        JOIN user_creneau uc ON uc.creneau_id = c.id
        WHERE uc.user_id = :userId
        AND c.date = :date
    ';

        $result = $conn->executeQuery($sql, [
            'userId' => $user->getId()->toBinary(),
            'date' => $date->format('Y-m-d'),
        ])->fetchOne();

        return $result !== null ? (int) $result : 0;
    }

}
