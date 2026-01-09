<?php

namespace App\Repository;

use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recipe>
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    /**
     * @return Recipe[] Devuelve un array de recetas activas (no eliminadas)
     */
    public function findAllActive(?int $typeId = null): array
    {
        $qb = $this->createQueryBuilder('r')
            ->andWhere('r.deleted_at IS NULL')
            ->orderBy('r.id', 'ASC');

        if ($typeId) {
             $qb->andWhere('r.recipe_type = :typeId')
                ->setParameter('typeId', $typeId);
        }

        return $qb->getQuery()->getResult();
    }
}
