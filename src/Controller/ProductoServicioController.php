<?php

namespace App\Controller;

use App\Entity\ProductoServicio;
use App\Form\ProductoServicioType;
use App\Repository\ProductoServicioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProductoServicioController extends AbstractController
{
    public function __construct(public readonly ProductoServicioRepository $repository) {}

    #[Route('/productos-servicios', name: 'app_producto_servicio', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('producto_servicio/index.html.twig', [
            'productos_servicios' => $this->repository->findAll(),
        ]);
    }

    #[Route('/productos-servicios/create', name: 'app_producto_servicio_create', methods: ['GET', 'POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager,
    ): Response {
        $productoServicio = new ProductoServicio();
        $form = $this->createForm(ProductoServicioType::class, $productoServicio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($productoServicio);
            $entityManager->flush();

            $this->addFlash('success', 'Producto creado exitosamente');
            return $this->redirectToRoute('app_producto_servicio_index');
        }

        return $this->render('producto_servicio/create.html.twig', [
            'form' => $form,
            'producto_servicio' => $productoServicio,
        ]);
    }
}
