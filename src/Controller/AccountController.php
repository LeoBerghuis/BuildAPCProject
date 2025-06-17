<?php

namespace App\Controller;

use App\Entity\Build;
use App\Entity\Category;
use App\Entity\Comments;

use App\Form\CommentTypeForm;
use App\Repository\CategoryRepository;
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
}
