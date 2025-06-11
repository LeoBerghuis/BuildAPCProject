<?php

namespace App\Controller;

use App\Entity\Build;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProductRepository;

final class BuildController extends AbstractController
{
    #[Route('/build', name: 'app_build')]
    public function index(Request $request, ProductRepository $productRepository, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        $categories = $entityManager->getRepository(Category::class)->findAll();
        if ($request->isMethod('POST')) {
            $session->set('pc_build', [
                'cpu' => $request->request->get('cpu'),
                'gpu' => $request->request->get('gpu'),
                'motherboard' => $request->request->get('motherboard'),
                'ram' => $request->request->get('ram'),
                'memory' => $request->request->get('memory'),
                'powersupply' => $request->request->get('powersupply'),
                'case' => $request->request->get('case'),
            ]);

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
    public function cart(EntityManagerInterface $entityManager,SessionInterface $session, ProductRepository $productRepository): Response
    {
        $total = 0;
        $build = $session->get('pc_build', []);
        $categories = $entityManager->getRepository(Category::class)->findAll();
        $products = [];
        foreach ($build as $component => $productId) {
            $product = $productRepository->find($productId);
            if ($product) {
                $products[$component] = $product;
            }
        }

        foreach ($products as $product) {
            $total += $product->getPrice();
        }
        return $this->render('build/cart.html.twig', [
            'build' => $products,
            'categories' => $categories,
            'total' => $total,
        ]);
    }

    #[Route('/cart-remove', name: 'app_cart_remove')]
    public function cartRemove(SessionInterface $session): Response
    {
        $session->remove('pc_build');
        return $this->redirectToRoute('app_build');
    }

    #[Route('/cart-create', name: 'app_cart_create')]
    public function cartCreate(SessionInterface $session, ProductRepository $productRepository, EntityManagerInterface $entityManager): Response {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $buildData = $session->get('pc_build', []);
        if (empty($buildData)) {
            return $this->redirectToRoute('app_cart');
        }
        $build = new Build();
        $build->setUser($user);
        $build->setName('Build by ' . $user->getUserIdentifier());
        $build->setIsPublic(true);
        $build->setCreatedAt(new \DateTime());

        foreach ($buildData as $productId) {
            $product = $productRepository->find($productId);
            if ($product) {
                $build->addProduct($product);
            }
        }

        $entityManager->persist($build);
        $entityManager->flush();

        $session->remove('pc_build');

        return $this->redirectToRoute('app_home');
    }
}
