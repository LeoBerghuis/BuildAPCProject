<?php

namespace App\Service;

use App\Entity\Build;
use App\Entity\Category;
use App\Entity\Comments;
use App\Form\BuildEditForm;
use App\Form\CommentTypeForm;
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

    public function removeBuild(EntityManagerInterface $entityManager, int $id): void
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
            'description' => null,
            'isPublic' => null,
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
        $selectedProducts['description'] = $build->getDescription();
        $selectedProducts['isPublic'] = $build->isPublic();

        $form = $this->createForm(BuildEditForm::class, $selectedProducts);



        return [$build, $categories, $form];

    }

    public function submitEdit(EntityManagerInterface $entityManager, int $id, $form): void
    {
        $build = $entityManager->find(Build::class, $id);

        foreach ($build->getProducts() as $product) {
            $build->removeProduct($product);
        }

        foreach (['cpu', 'gpu', 'motherboard', 'ram', 'memory', 'powersupply', 'case'] as $field) {
            $product = $form->get($field)->getData();
            if ($product) {
                $build->addProduct($product);
            }
        }

        $build->setDescription($form->get('description')->getData());
        $build->setIsPublic($form->get('isPublic')->getData());
        $entityManager->flush();
    }



    public function loadBuild(EntityManagerInterface $entityManager, int $id): array
    {
        $user = $this->getUser();

        $build = $entityManager->getRepository(Build::class)->find($id);

        $comments = $build->getComments();
        $categories = $entityManager->getRepository(Category::class)->findAll();

        $comment = new Comments();
        $form = $this->createForm(CommentTypeForm::class, $comment);

        return [$user, $build, $comments, $categories, $form, $comment];
    }

    public function postComment(Comments $comment, $user, $build, EntityManagerInterface $entityManager): void
    {
        $comment->setUser($user);
        $comment->setBuild($build);
        $comment->setCreatedAt(new \DateTime());

        $entityManager->persist($comment);
        $entityManager->flush();
    }

    public function removeComment(EntityManagerInterface $entityManager, int $id): void
    {
        $comment = $entityManager->getRepository(Comments::class)->find($id);

        $entityManager->remove($comment);
        $entityManager->flush();
    }
}