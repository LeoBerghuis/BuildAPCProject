<?php

namespace App\Controller;

use App\Entity\Build;
use App\Entity\Category;
use App\Form\BuildEditForm;
use App\Repository\CategoryRepository;
use App\Service\AccountService;
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

        return $this->render('security/account.html.twig', [
            'categories' => $categories,
            'builds' => $builds,
            'user' => $user,
        ]);
    }

    #[Route(path: '/account/build/{id}', name: 'app_build_view')]
    public function buildView(EntityManagerInterface $entityManager, int $id): Response
    {
        $user = $this->getUser();
        $builds = $entityManager->getRepository(Build::class)->findBy(['user' => $user,]);
        $categories = $entityManager->getRepository(Category::class)->findAll();
        return $this->render('security/account.html.twig', [
            'categories' => $categories,
            'builds' => $builds,
            'user' => $user,
        ]);
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
