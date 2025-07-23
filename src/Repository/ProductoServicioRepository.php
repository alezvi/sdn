<?php

namespace App\Repository;

use App\Entity\ProductoServicio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductoServicio>
 */
class ProductoServicioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductoServicio::class);
    }

    public function findAll(): array
    {
        return $this
            ->createQueryBuilder('p')
            ->getQuery()
            ->getResult();
    }
}
