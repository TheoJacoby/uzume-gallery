<?php

namespace App\Controller\Admin;

use App\Entity\Painting;
use App\Form\PaintingType;
use App\Repository\PaintingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/paintings')]
#[IsGranted('ROLE_ADMIN')]
class PaintingController extends AbstractController
{
    #[Route('', name: 'admin_painting_index', methods: ['GET'])]
    public function index(PaintingRepository $paintingRepository): Response
    {
        return $this->render('admin/painting/index.html.twig', [
            'paintings' => $paintingRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_painting_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $painting = new Painting();
        $form = $this->createForm(PaintingType::class, $painting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // Assigner l'utilisateur courant
                $painting->setUser($this->getUser());
                
                $entityManager->persist($painting);
                $entityManager->flush();

                $this->addFlash('success', 'La peinture a été créée avec succès !');
                return $this->redirectToRoute('admin_painting_index');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de la création de la peinture : ' . $e->getMessage());
            }
        }

        return $this->render('admin/painting/new.html.twig', [
            'painting' => $painting,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_painting_show', methods: ['GET'])]
    public function show(Painting $painting): Response
    {
        return $this->render('admin/painting/show.html.twig', [
            'painting' => $painting,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_painting_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Painting $painting, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PaintingType::class, $painting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // S'assurer que l'utilisateur est assigné
                if (!$painting->getUser()) {
                    $painting->setUser($this->getUser());
                }
                
                $entityManager->flush();

                $this->addFlash('success', 'La peinture a été modifiée avec succès !');
                return $this->redirectToRoute('admin_painting_index');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de la modification : ' . $e->getMessage());
            }
        }

        return $this->render('admin/painting/edit.html.twig', [
            'painting' => $painting,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'admin_painting_delete', methods: ['POST'])]
    public function delete(Request $request, Painting $painting, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$painting->getId(), $request->request->get('_token'))) {
            try {
                $entityManager->remove($painting);
                $entityManager->flush();

                $this->addFlash('success', 'La peinture a été supprimée avec succès !');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de la suppression : ' . $e->getMessage());
            }
        }

        return $this->redirectToRoute('admin_painting_index');
    }

    #[Route('/{id}/toggle-publish', name: 'admin_painting_toggle_publish', methods: ['POST'])]
    public function togglePublish(Request $request, Painting $painting, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('toggle'.$painting->getId(), $request->request->get('_token'))) {
            try {
                $painting->setIsPublished(!$painting->isPublished());
                $entityManager->flush();

                $status = $painting->isPublished() ? 'publiée' : 'masquée';
                $this->addFlash('success', "La peinture a été {$status} avec succès !");
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue : ' . $e->getMessage());
            }
        }

        return $this->redirectToRoute('admin_painting_index');
    }
}

