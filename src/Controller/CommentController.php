<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class CommentController extends AbstractController
{
    #[Route('/articles/{id}/comment', name: 'app_comment_add', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function add(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        // Vérifier le token CSRF
        if (!$this->isCsrfTokenValid('comment_form', $request->request->get('_csrf_token'))) {
            $this->addFlash('danger', 'Token CSRF invalide.');
            return $this->redirectToRoute('app_post_show', ['id' => $post->getId()]);
        }

        // Récupérer le contenu du textarea
        $content = trim($request->request->get('content') ?? '');
        
        if (empty($content)) {
            $this->addFlash('danger', 'Le commentaire ne peut pas être vide.');
            return $this->redirectToRoute('app_post_show', ['id' => $post->getId()]);
        }

        if (strlen($content) < 3) {
            $this->addFlash('danger', 'Le commentaire doit contenir au moins 3 caractères.');
            return $this->redirectToRoute('app_post_show', ['id' => $post->getId()]);
        }

        try {
            $comment = new Comment();
            $comment->setContent($content);
            $comment->setAuthor($this->getUser());
            $comment->setPost($post);
            $comment->setStatus(Comment::STATUS_PENDING);
            $comment->setCreatedAt(new \DateTime());
            
            $entityManager->persist($comment);
            $entityManager->flush();

            $this->addFlash('success', 'Commentaire ajouté avec succès ! En attente d\'approbation.');
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Erreur lors de l\'ajout du commentaire : ' . $e->getMessage());
        }

        return $this->redirectToRoute('app_post_show', ['id' => $post->getId()]);
    }

    #[Route('/comment/{id}/delete', name: 'app_comment_delete', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function delete(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response
    {
        $post = $comment->getPost();

        if ($comment->getAuthor() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas supprimer ce commentaire.');
        }

        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($comment);
            $entityManager->flush();
            $this->addFlash('success', 'Commentaire supprimé !');
        }

        return $this->redirectToRoute('app_post_show', ['id' => $post->getId()]);
    }
}
