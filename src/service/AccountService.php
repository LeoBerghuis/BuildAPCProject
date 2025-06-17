<?php

namespace App\Service;

use App\Entity\Build;
use App\Entity\Category;
use App\Form\BuildEditForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountService extends AbstractController
{
    public function getUserData(EntityManagerInterface $entityManager): array
    {
        $user = $this->getUser();
        $builds = $entityManager->getRepository(Build::class)->findBy(['user' => $user,]);
        $categories = $entityManager->getRepository(Category::class)->findAll();
        return [$user, $builds, $categories];
    }

    public function removeBuild(EntityManagerInterface $entityManager, int $id)
    {
        $build = $entityManager->getRepository(Build::class)->find($id);

        $entityManager->remove($build);
        $entityManager->flush();
    }

    public function editBuild(EntityManagerInterface $entityManager, int $id, Request $request): array
    {
        $build = $entityManager->find(Build::class, $id);
        $categories = $entityManager->getRepository(Category::class)->findAll();

        $selectedProducts = [
            'cpu' => null,
            'gpu' => null,
            'motherboard' => null,
            'ram' => null,
            'memory' => null,
            'powersupply' => null,
            'case' => null,
        ];

        foreach ($build->getProducts() as $product) {
            $categoryId = $product->getCategory()->getId();
            match ($categoryId) {
                1 => $selectedProducts['cpu'] = $product,
                2 => $selectedProducts['gpu'] = $product,
                3 => $selectedProducts['motherboard'] = $product,
                4 => $selectedProducts['ram'] = $product,
                5 => $selectedProducts['memory'] = $product,
                6 => $selectedProducts['powersupply'] = $product,
                7 => $selectedProducts['case'] = $product,
                default => null,
            };
        }

        $form = $this->createForm(BuildEditForm::class, $selectedProducts);

        return [$build, $categories, $form];

    }

    public function submitEdit(EntityManagerInterface $entityManager, int $id, $form)
    {
        $build = $entityManager->find(Build::class, $id);

            foreach ($build->getProducts() as $product) {
                $build->removeProduct($product);
            }

            foreach ($form->getData() as $product) {
                if ($product) {
                    $build->addProduct($product);
                }
            }

            $entityManager->flush();
        }
}