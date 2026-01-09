<?php

namespace App\Repository;

use App\Entity\Rating;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Rating>
 */
class RatingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rating::class);
    }

    public function hasUserRated(int $recipeId, string $ip): bool
    {
        $result = $this->createQueryBuilder('r')
            ->select('count(r.id)')
            ->where('r.recipe = :recipeId')
            ->andWhere('r.ip = :ip')
            ->setParameter('recipeId', $recipeId)
            ->setParameter('ip', $ip)
            ->getQuery()
            ->getSingleScalarResult();

        return $result > 0;
    }
}
