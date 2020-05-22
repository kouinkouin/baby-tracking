<?php

namespace App\Repository;

use App\Entity\BabyLogLine;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BabyLogLine|null find($id, $lockMode = null, $lockVersion = null)
 * @method BabyLogLine|null findOneBy(array $criteria, array $orderBy = null)
 * @method BabyLogLine[]    findAll()
 * @method BabyLogLine[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BabyLogLineRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BabyLogLine::class);
    }

    public function findOneLastByUser(User $user): ?BabyLogLine
    {
        return $this->createQueryBuilder('baby_log_line')
            ->join('baby_log_line.baby', 'baby')
            ->where('baby.user = :user')
            ->setMaxResults(1)
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
