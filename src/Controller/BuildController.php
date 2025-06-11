<?php

namespace App\Controller;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProductRepository;

final class BuildController extends AbstractController
{
    #[Route('/build', name: 'app_build')]
    public function index(ProductRepository $ProductRepository, EntityManagerInterface $entitymanager): Response
    {
        $categories = $entitymanager->getRepository(Category::class)->findAll();
        return $this->render('build/index.html.twig', [
            'cpus' => $ProductRepository->findBy(['category' => 1]),
            'gpu' => $ProductRepository->findBy(['category' => 2]),
            'motherboards' => $ProductRepository->findBy(['category' => 3]),
            'rams' => $ProductRepository->findBy(['category' => 4]),
            'memory' => $ProductRepository->findBy(['category' => 5]),
            'powersupply' => $ProductRepository->findBy(['category' => 6]),
            'case' => $ProductRepository->findBy(['category' => 7]),
            'categories' => $categories,

        ]);
    }

    #[Route('/build/show', name: 'build_show')]
    public function show(ProductRepository $ProductRepository, EntityManagerInterface $entitymanager): Response
    {
        $categories = $entitymanager->getRepository(Category::class)->findAll();
        return $this->render('build/show.html.twig', [
            'cpus' => $ProductRepository->findBy(['category' => 1]),
            'gpu' => $ProductRepository->findBy(['category' => 2]),
            'motherboards' => $ProductRepository->findBy(['category' => 3]),
            'rams' => $ProductRepository->findBy(['category' => 4]),
            'memory' => $ProductRepository->findBy(['category' => 5]),
            'powersupply' => $ProductRepository->findBy(['category' => 6]),
            'case' => $ProductRepository->findBy(['category' => 7]),
            'categories' => $categories,

        ]);
    }

}
