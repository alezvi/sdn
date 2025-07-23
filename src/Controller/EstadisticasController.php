<?php

namespace App\Controller;

use App\Enum\TimePeriodsEnum;
use App\Enum\TiposDeItems;
use App\Repository\ProductoServicioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EstadisticasController extends AbstractController
{
    public function __construct(public readonly ProductoServicioRepository $repository) {}

    #[Route('/estadisticas', name: 'app_estadisticas')]
    public function index(Request $request): Response
    {
        $periodo = $request->query->get('periodo', 'month');
        $tipo = $request->query->get('tipo', null);
        $limit = $request->query->get('limit', 12);

        $periodosValidos = TimePeriodsEnum::cases();

        if (!in_array($periodo, $periodosValidos)) {
            $periodo = 'month';
        }

        if ($tipo && !in_array($tipo, TiposDeItems::getChoices())) {
            $tipo = null;
        }

        try {
            $estadisticas = $this->repository->getCountByPeriod($periodo, $tipo, (int)$limit);
            $resumen = $this->repository->getStatisticsSummary();
            $recientes = $this->repository->findRecentByType($tipo, 5);
        } catch (\Exception $e) {
            // En caso de error, usar valores por defecto
            $estadisticas = [];

            $resumen = [
                'total' => 0,
                'productos' => 0,
                'servicios' => 0,
                'hoy' => 0,
                'ayer' => 0,
                'esta_semana' => 0,
                'semana_pasada' => 0,
                'este_mes' => 0,
                'mes_pasado' => 0,
                'este_año' => 0,
            ];

            $recientes = [];

            $this->addFlash('error', 'Error al cargar estadísticas: ' . $e->getMessage());
        }

        $chartData = $this->prepareChartData($estadisticas);

        return $this->render('estadisticas/index.html.twig', [
            'productos_servicios' => $this->repository->findAll(),
            'estadisticas' => $estadisticas,
            'resumen' => $resumen,
            'recientes' => $recientes,
            'chart_data' => $chartData,
            'filtros' => [
                'periodo' => $periodo,
                'tipo' => $tipo,
                'limit' => $limit,
            ],
        ]);
    }

    /**
     * Datos en formato para ChartJS
     *
     * @param array $estadisticas
     * @return array
     */
    private function prepareChartData(array $estadisticas): array
    {
        $labels = [];
        $data = [];

        foreach ($estadisticas as $stat) {
            $labels[] = $this->formatPeriodLabel($stat['periodo']);
            $data[] = (int)$stat['cantidad'];
        }

        return [
            'labels' => array_reverse($labels),
            'data' => array_reverse($data),
        ];
    }

    private function formatPeriodLabel(string $periodo): string
    {
        // Si es una fecha completa (YYYY-MM-DD)
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $periodo)) {
            return date('d/m', strtotime($periodo));
        }

        // Si es año-mes (YYYY-MM)
        if (preg_match('/^\d{4}-\d{2}$/', $periodo)) {
            return date('M Y', strtotime($periodo . '-01'));
        }

        // Si es año-semana (YYYY-WW)
        if (preg_match('/^\d{4}-\d{2}$/', $periodo)) {
            return 'Sem ' . substr($periodo, -2);
        }

        // Si es solo año
        if (preg_match('/^\d{4}$/', $periodo)) {
            return $periodo;
        }

        return $periodo;
    }
}
