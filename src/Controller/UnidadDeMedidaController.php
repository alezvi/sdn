<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UnidadDeMedidaController extends AbstractController
{
    #[Route('/unidades-de-medida', name: 'app_unidad_de_medida')]
    public function index(): Response
    {
        return $this->render('unidad_de_medida/index.html.twig', [
            'controller_name' => UnidadDeMedidaController::class,
        ]);
    }
}
