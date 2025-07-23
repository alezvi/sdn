<?php

namespace App\Repository;

use App\Entity\UnidadDeMedida;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UnidadDeMedida>
 */
class UnidadDeMedidaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UnidadDeMedida::class);
    }

    public function findAll(): array
    {
        return $this->createQueryBuilder('u')
            ->getQuery()
            ->getResult();
    }
}
