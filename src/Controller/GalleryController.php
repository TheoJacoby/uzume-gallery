<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Painting;
use App\Form\CommentType;
use App\Repository\CategoryRepository;
use App\Repository\PaintingRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GalleryController extends AbstractController
{
    #[Route('/gallery', name: 'app_gallery')]
    public function index(
        PaintingRepository $paintingRepository,
        CategoryRepository $categoryRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        // Récupération des paramètres de recherche et tri
        // Utiliser 'sortBy' et 'sortOrder' au lieu de 'sort' et 'direction' pour éviter le conflit avec KnpPaginator
        $search = $request->query->get('search', '');
        $categoryId = $request->query->getInt('category', 0) ?: null;
        $sortBy = $request->query->get('sortBy', $request->query->get('sort', 'created')); // Fallback sur 'sort' pour compatibilité
        $sortOrder = $request->query->get('sortOrder', $request->query->get('order', $request->query->get('direction', 'DESC'))); // Fallback sur 'order' ou 'direction'

        // Construction de la requête avec recherche et tri
        $queryBuilder = $paintingRepository->findPublishedWithSearchAndSort(
            $search ?: null,
            $categoryId,
            $sortBy,
            $sortOrder
        );

        // Créer une nouvelle requête sans les paramètres de tri pour KnpPaginator
        // Cela évite que KnpPaginator essaie de trier automatiquement
        $query = $queryBuilder->getQuery();

        // Pagination - passer la Query directement (le tri est déjà appliqué)
        $paintings = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            6 // 6 peintures par page
        );

        // Récupération de toutes les catégories pour le filtre
        $categories = $categoryRepository->findAll();

        return $this->render('gallery/index.html.twig', [
            'paintings' => $paintings,
            'categories' => $categories,
            'currentSearch' => $search,
            'currentCategory' => $categoryId,
            'currentSort' => $sortBy,
            'currentOrder' => $sortOrder,
        ]);
    }

    #[Route('/gallery/{id}', name: 'app_gallery_show', requirements: ['id' => '\d+'])]
    public function show(
        Painting $painting,
        Request $request,
        EntityManagerInterface $entityManager,
        CommentRepository $commentRepository
    ): Response {
        // Récupérer les commentaires visibles
        $comments = $commentRepository->findVisibleByPainting($painting->getId());

        // Créer le formulaire de commentaire
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $comment->setPainting($painting);
                $entityManager->persist($comment);
                $entityManager->flush();

                $this->addFlash('success', 'Votre commentaire a été ajouté avec succès !');
                return $this->redirectToRoute('app_gallery_show', ['id' => $painting->getId()]);
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de l\'ajout du commentaire : ' . $e->getMessage());
            }
        }

        return $this->render('gallery/show.html.twig', [
            'painting' => $painting,
            'comments' => $comments,
            'form' => $form,
        ]);
    }
}

