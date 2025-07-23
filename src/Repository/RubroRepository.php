<?php

namespace App\Repository;

use App\Entity\Rubro;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Rubro>
 */
class RubroRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rubro::class);
    }

    public function findAll(): array
    {
        return $this->createQueryBuilder('r')
            ->getQuery()
            ->getResult();
    }
}
