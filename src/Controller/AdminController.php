<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Comment;
use App\Entity\Category;
use App\Entity\Post;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Repository\CommentRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
final class AdminController extends AbstractController
{
    #[Route('', name: 'app_admin_dashboard', methods: ['GET'])]
    public function dashboard(PostRepository $postRepo, UserRepository $userRepo, CommentRepository $commentRepo): Response
    {
        return $this->render('admin/dashboard.html.twig', [
            'totalPosts' => count($postRepo->findAll()),
            'totalUsers' => count($userRepo->findAll()),
            'pendingComments' => count($commentRepo->findBy(['status' => Comment::STATUS_PENDING])),
        ]);
    }

    #[Route('/users', name: 'app_admin_users', methods: ['GET'])]
    public function users(UserRepository $userRepository): Response
    {
        return $this->render('admin/users.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/users/{id}/toggle', name: 'app_admin_toggle_user', methods: ['POST'])]
    public function toggleUser(User $user, EntityManagerInterface $em): Response
    {
        $user->setIsActive(!$user->isActive());
        $em->flush();

        $this->addFlash('success', 'Statut utilisateur mis à jour !');
        return $this->redirectToRoute('app_admin_users');
    }

    #[Route('/users/{id}/role', name: 'app_admin_promote_user', methods: ['POST'])]
    public function promoteUser(User $user, EntityManagerInterface $em): Response
    {
        $roles = $user->getRoles();
        if (in_array('ROLE_ADMIN', $roles)) {
            $roles = array_filter($roles, fn($r) => $r !== 'ROLE_ADMIN');
        } else {
            $roles[] = 'ROLE_ADMIN';
        }
        $user->setRoles($roles);
        $em->flush();

        $this->addFlash('success', 'Rôle utilisateur mis à jour !');
        return $this->redirectToRoute('app_admin_users');
    }

    #[Route('/comments', name: 'app_admin_comments', methods: ['GET'])]
    public function comments(CommentRepository $commentRepository, Request $request): Response
    {
        $status = $request->query->get('status', Comment::STATUS_PENDING);
        
        $comments = match($status) {
            Comment::STATUS_APPROVED => $commentRepository->findBy(['status' => Comment::STATUS_APPROVED]),
            Comment::STATUS_REJECTED => $commentRepository->findBy(['status' => Comment::STATUS_REJECTED]),
            default => $commentRepository->findBy(['status' => Comment::STATUS_PENDING]),
        };

        return $this->render('admin/comments.html.twig', [
            'comments' => $comments,
            'status' => $status,
        ]);
    }

    #[Route('/comments/{id}/approve', name: 'app_admin_approve_comment', methods: ['POST'])]
    public function approveComment(Comment $comment, EntityManagerInterface $em): Response
    {
        $comment->setStatus(Comment::STATUS_APPROVED);
        $em->flush();

        $this->addFlash('success', 'Commentaire approuvé !');
        return $this->redirectToRoute('app_admin_comments');
    }

    #[Route('/comments/{id}/reject', name: 'app_admin_reject_comment', methods: ['POST'])]
    public function rejectComment(Comment $comment, EntityManagerInterface $em): Response
    {
        $comment->setStatus(Comment::STATUS_REJECTED);
        $em->flush();

        $this->addFlash('success', 'Commentaire rejeté !');
        return $this->redirectToRoute('app_admin_comments');
    }

    #[Route('/comments/{id}/delete', name: 'app_admin_delete_comment', methods: ['POST'])]
    public function deleteComment(Comment $comment, EntityManagerInterface $em): Response
    {
        $em->remove($comment);
        $em->flush();

        $this->addFlash('success', 'Commentaire supprimé !');
        return $this->redirectToRoute('app_admin_comments');
    }

    #[Route('/posts', name: 'app_admin_posts', methods: ['GET'])]
    public function posts(PostRepository $postRepository): Response
    {
        return $this->render('admin/posts.html.twig', [
            'posts' => $postRepository->findAll(),
        ]);
    }

    #[Route('/posts/{id}/delete', name: 'app_admin_delete_post', methods: ['POST'])]
    public function deletePost(Post $post, EntityManagerInterface $em): Response
    {
        $em->remove($post);
        $em->flush();

        $this->addFlash('success', 'Article supprimé !');
        return $this->redirectToRoute('app_admin_posts');
    }

    #[Route('/categories', name: 'app_admin_categories', methods: ['GET'])]
    public function categories(CategoryRepository $categoryRepository): Response
    {
        return $this->render('admin/categories.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    #[Route('/categories/{id}/delete', name: 'app_admin_delete_category', methods: ['POST'])]
    public function deleteCategory(Category $category, EntityManagerInterface $em): Response
    {
        $em->remove($category);
        $em->flush();

        $this->addFlash('success', 'Catégorie supprimée !');
        return $this->redirectToRoute('app_admin_categories');
    }
}
