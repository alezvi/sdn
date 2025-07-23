<?php

namespace App\Repository;

use App\Entity\ProductoServicio;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use PDO;

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

    /**
     * @param string $periodo 'day', 'week', 'month', 'year'
     * @param string|null $tipo 'P' para productos, 'S' para servicios, null para ambos
     * @param int|null $limit Límite de resultados (null = sin límite)
     * @return array
     */
    public function getCountByPeriod(string $periodo, ?string $tipo = null, ?int $limit = null): array
    {
        $connection = $this->getEntityManager()->getConnection();

        switch (strtolower($periodo)) {
            case 'day':
                $sql = "SELECT DATE(created_at) as periodo, COUNT(*) as cantidad
                        FROM producto_servicio
                        WHERE 1=1";
                $groupBy = "GROUP BY DATE(created_at)";
                $orderBy = "ORDER BY DATE(created_at) DESC";
                break;

            case 'week':
                $sql = "SELECT CONCAT(YEAR(created_at), '-', LPAD(WEEK(created_at, 1), 2, '0')) as periodo, COUNT(*) as cantidad
                        FROM producto_servicio
                        WHERE 1=1";
                $groupBy = "GROUP BY YEAR(created_at), WEEK(created_at, 1)";
                $orderBy = "ORDER BY YEAR(created_at) DESC, WEEK(created_at, 1) DESC";
                break;

            case 'month':
                $sql = "SELECT DATE_FORMAT(created_at, '%Y-%m') as periodo, COUNT(*) as cantidad
                        FROM producto_servicio
                        WHERE 1=1";
                $groupBy = "GROUP BY DATE_FORMAT(created_at, '%Y-%m')";
                $orderBy = "ORDER BY DATE_FORMAT(created_at, '%Y-%m') DESC";
                break;

            case 'year':
                $sql = "SELECT YEAR(created_at) as periodo, COUNT(*) as cantidad
                        FROM producto_servicio
                        WHERE 1=1";
                $groupBy = "GROUP BY YEAR(created_at)";
                $orderBy = "ORDER BY YEAR(created_at) DESC";
                break;

            default:
                throw new \InvalidArgumentException('Períodos válidos: day, week, month, year');
        }

        $params = [];
        if ($tipo !== null) {
            $sql .= " AND tipo = :tipo";
            $params['tipo'] = $tipo;
        }

        $sql .= " " . $groupBy . " " . $orderBy;

        if ($limit !== null) {
            $sql .= " LIMIT :limit";
            $params['limit'] = $limit;
        }

        $stmt = $connection->prepare($sql);

        foreach ($params as $key => $value) {
            if ($key === 'limit') {
                $stmt->bindValue($key, $value, PDO::PARAM_INT);
            } else {
                $stmt->bindValue($key, $value);
            }
        }

        return $stmt->executeQuery()->fetchAllAssociative();
    }

    /**
     * Obtiene resumen completo de estadísticas
     */
    public function getStatisticsSummary(): array
    {
        $today = new DateTime();
        $yesterday = (clone $today)->modify('-1 day');
        $thisWeek = (clone $today)->modify('monday this week');
        $lastWeek = (clone $thisWeek)->modify('-1 week');
        $thisMonth = (clone $today)->modify('first day of this month');
        $lastMonth = (clone $thisMonth)->modify('-1 month');
        $thisYear = (clone $today)->modify('first day of January this year');

        return [
            'hoy' => $this->getCountForDate($today),
            'ayer' => $this->getCountForDate($yesterday),
            'esta_semana' => $this->getCountForDateRange($thisWeek, $today),
            'semana_pasada' => $this->getCountForDateRange($lastWeek, (clone $thisWeek)->modify('-1 day')),
            'este_mes' => $this->getCountForDateRange($thisMonth, $today),
            'mes_pasado' => $this->getCountForDateRange($lastMonth, (clone $thisMonth)->modify('-1 day')),
            'este_año' => $this->getCountForDateRange($thisYear, $today),
            'total' => $this->count([]),
            'productos' => $this->count(['tipo' => 'P']),
            'servicios' => $this->count(['tipo' => 'S']),
        ];
    }

    /**
     * Cuenta registros para una fecha específica
     */
    private function getCountForDate(\DateTimeInterface $fecha): int
    {
        $inicio = (clone $fecha)->setTime(0, 0, 0);
        $fin = (clone $fecha)->setTime(23, 59, 59);

        return $this
            ->createQueryBuilder('p')
            ->select('COUNT(p.id_producto_servicio)')
            ->andWhere('p.created_at >= :inicio')
            ->andWhere('p.created_at <= :fin')
            ->setParameter('inicio', $inicio)
            ->setParameter('fin', $fin)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Cuenta registros para un rango de fechas
     */
    private function getCountForDateRange(\DateTimeInterface $inicio, \DateTimeInterface $fin): int
    {
        $inicioDay = (clone $inicio)->setTime(0, 0, 0);
        $finDay = (clone $fin)->setTime(23, 59, 59);

        return $this
            ->createQueryBuilder('p')
            ->select('COUNT(p.id_producto_servicio)')
            ->andWhere('p.created_at >= :inicio')
            ->andWhere('p.created_at <= :fin')
            ->setParameter('inicio', $inicioDay)
            ->setParameter('fin', $finDay)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Obtiene los productos/servicios más recientes por tipo
     */
    public function findRecentByType(?string $tipo = null, int $limit = 10): array
    {
        $qb = $this
            ->createQueryBuilder('p')
            ->orderBy('p.created_at', 'DESC')
            ->setMaxResults($limit);

        if ($tipo !== null) {
            $qb
                ->andWhere('p.tipo = :tipo')
                ->setParameter('tipo', $tipo);
        }

        return $qb->getQuery()->getResult();
    }

}
