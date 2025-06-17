<?php

namespace App\Form;

use App\Entity\Products;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class BuildEditForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cpu', EntityType::class, [
                'class' => Products::class,
                'choice_label' => 'name',
                'query_builder' => fn($er) => $er->createQueryBuilder('p')->where('p.category = 1'),
                'required' => false,
                'placeholder' => '-- Select CPU --',
            ])
            ->add('gpu', EntityType::class, [
                'class' => Products::class,
                'choice_label' => 'name',
                'query_builder' => fn($er) => $er->createQueryBuilder('p')->where('p.category = 2'),
                'required' => false,
                'placeholder' => '-- Select GPU --',
            ])
            ->add('motherboard', EntityType::class, [
                'class' => Products::class,
                'choice_label' => 'name',
                'query_builder' => fn($er) => $er->createQueryBuilder('p')->where('p.category = 3'),
                'required' => false,
                'placeholder' => '-- Select Motherboard --',
            ])
            ->add('ram', EntityType::class, [
                'class' => Products::class,
                'choice_label' => 'name',
                'query_builder' => fn($er) => $er->createQueryBuilder('p')->where('p.category = 4'),
                'required' => false,
                'placeholder' => '-- Select RAM --',
            ])
            ->add('memory', EntityType::class, [
                'class' => Products::class,
                'choice_label' => 'name',
                'query_builder' => fn($er) => $er->createQueryBuilder('p')->where('p.category = 5'),
                'required' => false,
                'placeholder' => '-- Select Memory --',
            ])
            ->add('powersupply', EntityType::class, [
                'class' => Products::class,
                'choice_label' => 'name',
                'query_builder' => fn($er) => $er->createQueryBuilder('p')->where('p.category = 6'),
                'required' => false,
                'placeholder' => '-- Select Power Supply --',
            ])
            ->add('case', EntityType::class, [
                'class' => Products::class,
                'choice_label' => 'name',
                'query_builder' => fn($er) => $er->createQueryBuilder('p')->where('p.category = 7'),
                'required' => false,
                'placeholder' => '-- Select Case --',
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'attr' => ['rows' => 4, 'placeholder' => 'Enter a description for your build...'],
                'label' => 'Build Description',
            ])
            ->add('isPublic', CheckboxType::class, [
                'required' => false,
                'label' => 'Make this build public',
            ]);
    }
}
