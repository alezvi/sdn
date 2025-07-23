<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RubroController extends AbstractController
{
    #[Route('/rubros', name: 'app_rubro')]
    public function index(): Response
    {
        return $this->render('rubro/index.html.twig', [
            'controller_name' => RubroController::class,
        ]);
    }
}
