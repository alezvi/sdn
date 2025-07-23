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
//            $entityManager->flush();

            $this->addFlash('success', 'Producto creado exitosamente');
            return $this->redirectToRoute('app_producto_servicio');
        }

        return $this->render('producto_servicio/create.html.twig', [
            'form' => $form,
            'producto_servicio' => $productoServicio,
        ]);
    }

    #[Route('/productos-servicios/{id}/edit', name: 'app_producto_servicio_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        ProductoServicio $productoServicio,
        EntityManagerInterface $entityManager,
    ): Response {
        $form = $this->createForm(ProductoServicioType::class, $productoServicio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Producto/Servicio actualizado exitosamente');
            return $this->redirectToRoute('app_producto_servicio');
        }

        return $this->render('producto_servicio/edit.html.twig', [
            'form' => $form,
            'producto_servicio' => $productoServicio,
        ]);
    }

    #[Route('/productos-servicios/{id}/delete', name: 'app_producto_servicio_delete', methods: ['GET', 'POST'])]
    public function delete(
        Request $request,
        ProductoServicio $productoServicio,
        EntityManagerInterface $entityManager,
    ): Response {
        if ($request->isMethod('GET')) {
            return $this->render('producto_servicio/delete.html.twig', [
                'producto_servicio' => $productoServicio,
            ]);
        }

        // Si es POST, proceder con la eliminación
        if ($request->isMethod('POST')) {
            try {
                $nombre = $productoServicio->getProductoServicio();
                $entityManager->remove($productoServicio);
                $entityManager->flush();

                $this->addFlash('success', "El producto/servicio '{$nombre}' ha sido eliminado exitosamente");
            } catch (\Exception $e) {
                $this->addFlash('error', 'Error al eliminar el producto/servicio: ' . $e->getMessage());
            }

            return $this->redirectToRoute('app_producto_servicio');
        }

        return $this->redirectToRoute('app_producto_servicio');
    }

    #[Route('/productos-servicios/{id}', name: 'app_producto_servicio_show', methods: ['GET'])]
    public function show(ProductoServicio $productoServicio): Response
    {
        return $this->render('producto_servicio/show.html.twig', [
            'producto_servicio' => $productoServicio,
        ]);
    }
}
