<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Products;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProductController extends AbstractController
{
    #[Route('/product/{id}', name: 'app_product')]
    public function index(int $id, EntityManagerInterface $entitymanager): Response
    {
        $categories = $entitymanager->getRepository(Category::class)->findAll();
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
            'category_id' => $id,
            'categories' => $categories,
//            'product' => $product
        ]);
    }

}
