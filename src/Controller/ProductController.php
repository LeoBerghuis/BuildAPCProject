<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Products;
use App\Form\ProductForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProductController extends AbstractController
{
    #[Route('/product/new', name: 'product_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new Products();
        $form = $this->createForm(ProductForm::class, $product);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('app_home', ['id' => $product->getId()]);
        }

        $categories = $entityManager->getRepository(Category::class)->findAll();
        return $this->render('product/new.html.twig', [
            'form' => $form->createView(),
            'categories' => $categories,
        ]);
    }
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
