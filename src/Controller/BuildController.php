<?php

namespace App\Controller;

use App\Entity\Build;
use App\Entity\Category;
use App\Entity\Products;
use App\Form\BuildEditForm;
use App\Repository\CategoryRepository;
use App\Service\BuildService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProductsRepository;

final class BuildController extends AbstractController
{
    #[Route('/build', name: 'app_build')]
    public function index(Request $request, ProductsRepository $productRepository, EntityManagerInterface $entityManager, SessionInterface $session, BuildService $buildService): Response
    {
        $categories = $entityManager->getRepository(Category::class)->findAll();
        if ($request->isMethod('POST')) {
            $buildService->addToSession($request, $session);
            return $this->redirectToRoute('app_cart');
        }

        return $this->render('build/index.html.twig', [
            'cpus' => $productRepository->findBy(['category' => 1]),
            'gpu' => $productRepository->findBy(['category' => 2]),
            'motherboards' => $productRepository->findBy(['category' => 3]),
            'rams' => $productRepository->findBy(['category' => 4]),
            'memory' => $productRepository->findBy(['category' => 5]),
            'powersupply' => $productRepository->findBy(['category' => 6]),
            'case' => $productRepository->findBy(['category' => 7]),
            'categories' => $categories,
        ]);
    }

    #[Route('/cart', name: 'app_cart')]
    public function cart(EntityManagerInterface $entityManager, SessionInterface $session, ProductsRepository $productsRepository, BuildService $buildService): Response
    {
        [$total, $products, $categories] = $buildService->getcartAndTotal($session, $entityManager, $productsRepository);
        return $this->render('build/cart.html.twig', [
            'build' => $products,
            'categories' => $categories,
            'total' => $total,
        ]);
    }

    #[Route('/cart-remove', name: 'app_cart_remove')]
    public function cartRemove(SessionInterface $session, BuildService $buildService): Response
    {
        $buildService->removeCartFromSession($session);
        return $this->redirectToRoute('app_build');
    }

    #[Route('/cart-create', name: 'app_cart_create')]
    public function cartCreate(BuildService $buildService, SessionInterface $session, ProductsRepository $productsRepository, EntityManagerInterface $entityManager): Response
    {
        $buildService->createCart($session, $productsRepository, $entityManager);
        return $this->redirectToRoute('app_home');
    }
}
