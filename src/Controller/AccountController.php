<?php

namespace App\Controller;

use App\Entity\Build;
use App\Entity\Category;
use App\Entity\Comments;

use App\Entity\User;
use App\Form\CommentTypeForm;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use App\service\AccountService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AccountController extends AbstractController
{
    #[Route(path: '/account', name: 'app_account')]
    public function account(EntityManagerInterface $entityManager, AccountService $accountService): Response
    {
        [$user, $builds, $categories] = $accountService->getUserData($entityManager);
        return $this->render('account/index.html.twig', [
            'categories' => $categories,
            'builds' => $builds,
            'user' => $user,
        ]);
    }

    #[Route(path: '/account/build/{id}', name: 'app_build_view')]
    public function buildView(EntityManagerInterface $entityManager, int $id, Request $request, AccountService $accountService): Response
    {
        [$user, $build, $comments, $categories, $form, $comment] = $accountService->loadBuild($entityManager, $id);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $accountService->postComment($comment, $user, $build, $entityManager);
            return $this->redirectToRoute('app_build_view', ['id' => $id]);
        }
        return $this->render('account/build-view.html.twig', [
            'categories' => $categories,
            'build' => $build,
            'user' => $user,
            'comments' => $comments,
            'commentForm' => $form->createView(),
        ]);
    }

    #[Route('/comment-remove/{id}', name: 'app_remove_comment')]
    public function commentRemove(EntityManagerInterface $entityManager, int $id, AccountService $accountService): Response
    {
        $accountService->removeComment($entityManager, $id);
        return $this->redirectToRoute('app_account');
    }

    #[Route('/build-edit/{id}', name: 'app_build_edit')]
    public function edit(int $id, Request $request, EntityManagerInterface $entityManager, AccountService $accountService): Response
    {
        [$build, $categories, $form] = $accountService->editBuild($entityManager, $id, $request);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $accountService->submitEdit($entityManager, $id, $form);
            return $this->redirectToRoute('app_account');
        }
        return $this->render('build/edit.html.twig', [
            'form' => $form->createView(),
            'categories' => $categories,
            'build' => $build,
        ]);
    }

    #[Route('/build-remove/{id}', name: 'app_build_remove')]
    public function buildRemove(EntityManagerInterface $entityManager, int $id, AccountService $accountService): Response
    {
       $accountService->removeBuild($entityManager, $id);
        return $this->redirectToRoute('app_account');
    }

    #[Route('/admin/dashboard', name: 'app_admin_dashboard')]
    public function adminDashboard(UserRepository $userRepository, EntityManagerInterface $entityManager, AccountService $accountService): Response
    {
        $users = $userRepository->findAll();
        [$user, $builds, $categories] = $accountService->getUserData($entityManager);
        return $this->render('account/dashboard.html.twig', [
            'users' => $users,
            'categories' => $categories,
        ]);
    }

    #[Route('/admin/user/{id}/delete', name: 'admin_user_delete', methods: ['POST'])]
    public function deleteUser(Request $request, int $id, EntityManagerInterface $em): RedirectResponse
    {
        $user = $em->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found.');
        }

// Delete related comments
        $comments = $em->getRepository(Comment::class)->findBy(['user' => $user]);
        foreach ($comments as $comment) {
            $em->remove($comment);
        }

        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('app_admin_dashboard');
    }
}
