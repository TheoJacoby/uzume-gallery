<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/comments')]
#[IsGranted('ROLE_ADMIN')]
class CommentController extends AbstractController
{
    #[Route('', name: 'admin_comment_index', methods: ['GET'])]
    public function index(CommentRepository $commentRepository): Response
    {
        return $this->render('admin/comment/index.html.twig', [
            'comments' => $commentRepository->findAll(),
        ]);
    }

    #[Route('/{id}/toggle-visibility', name: 'admin_comment_toggle_visibility', methods: ['POST'])]
    public function toggleVisibility(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('toggle'.$comment->getId(), $request->request->get('_token'))) {
            try {
                $comment->setIsVisible(!$comment->isVisible());
                $entityManager->flush();

                $status = $comment->isVisible() ? 'visible' : 'masqué';
                $this->addFlash('success', "Le commentaire a été {$status} avec succès !");
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue : ' . $e->getMessage());
            }
        }

        return $this->redirectToRoute('admin_comment_index');
    }
}

