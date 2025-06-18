<?php

namespace App\Service;

use App\Entity\Build;
use App\Entity\Category;
use App\Entity\Products;
use App\Repository\ProductRepository;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Void_;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BuildService extends AbstractController
{
    public function __construct(
    ) {}

    public function addToSession(Request $request, SessionInterface $session): Void
    {
            $session->set('pc_build', [
                'cpu' => $request->request->get('cpu'),
                'gpu' => $request->request->get('gpu'),
                'motherboard' => $request->request->get('motherboard'),
                'ram' => $request->request->get('ram'),
                'memory' => $request->request->get('memory'),
                'powersupply' => $request->request->get('powersupply'),
                'case' => $request->request->get('case'),
            ]);
    }

    public function getcartAndTotal(SessionInterface $session, EntityManagerInterface $entityManager, ProductsRepository $productsRepository)
    {
        $total = 0;
        $build = $session->get('pc_build', []);
        $categories = $entityManager->getRepository(Category::class)->findAll();
        $products = [];
        foreach ($build as $component => $productId) {
            $product = $productsRepository->find($productId);
            if ($product) {
                $products[$component] = $product;
            }
        }

        foreach ($products as $product) {
            $total += $product->getPrice();
        }
        return [$total, $products, $categories];
    }

    public function removeCartFromSession(SessionInterface $session): void
    {
        $session->remove('pc_build');
    }

    public function createCart(SessionInterface $session, ProductsRepository $productsRepository, EntityManagerInterface $entityManager): void
    {
        $user = $this->getUser();
        $buildData = $session->get('pc_build', []);
        $build = new Build();
        $build->setUser($user);
        $build->setName('Build by ' . $user->getUserIdentifier());
        $build->setIsPublic(true);
        $build->setCreatedAt(new \DateTime());

        foreach ($buildData as $productId) {
            $product = $productsRepository->find($productId);
            if ($product) {
                $build->addProduct($product);
            }
        }

        $entityManager->persist($build);
        $entityManager->flush();

        $session->remove('pc_build');
    }

    public function filterBuilds($selectedCategoryId, array &$builds, array $totalPrices): void
    {
        if ($selectedCategoryId == 1) {
            usort($builds, function ($a, $b) use ($totalPrices) {
                return $totalPrices[$b->getId()] <=> $totalPrices[$a->getId()];
            });
        } elseif ($selectedCategoryId == 2) {
            usort($builds, function ($a, $b) use ($totalPrices) {
                return $totalPrices[$a->getId()] <=> $totalPrices[$b->getId()];
            });
        } elseif ($selectedCategoryId == 3) {
            usort($builds, function ($a, $b) {
                return $a->getCreatedAt() <=> $b->getCreatedAt();
            });
        } elseif ($selectedCategoryId == 4) {
            usort($builds, function ($a, $b) {
                return $b->getCreatedAt() <=> $a->getCreatedAt();
            });
        }
    }
}
