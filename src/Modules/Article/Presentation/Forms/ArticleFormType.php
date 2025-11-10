<?php

declare(strict_types=1);

namespace App\Modules\Article\Presentation\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


final class ArticleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('heading', TextType::class, [
                'label' => 'Titre',
                'attr' => [
                    'placeholder' => 'Titre de l\'article',
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new Assert\NotBlank(message: 'Le titre est obligatoire'),
                    new Assert\Length(
                        min: 3,
                        max: 255,
                        minMessage: 'Le titre doit contenir au moins {{ limit }} caractères',
                        maxMessage: 'Le titre ne peut pas dépasser {{ limit }} caractères'
                    ),
                ],
            ])
            ->add('subheading', TextType::class, [
                'label' => 'Sous-titre',
                'attr' => [
                    'placeholder' => 'Sous-titre de l\'article',
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new Assert\NotBlank(message: 'Le sous-titre est obligatoire'),
                    new Assert\Length(
                        min: 3,
                        max: 255,
                        minMessage: 'Le sous-titre doit contenir au moins {{ limit }} caractères',
                        maxMessage: 'Le sous-titre ne peut pas dépasser {{ limit }} caractères'
                    ),
                ],
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu',
                'attr' => [
                    'placeholder' => 'Contenu de l\'article',
                    'class' => 'form-control',
                    'rows' => 10,
                ],
                'constraints' => [
                    new Assert\NotBlank(message: 'Le contenu est obligatoire'),
                    new Assert\Length(
                        min: 10,
                        minMessage: 'Le contenu doit contenir au moins {{ limit }} caractères'
                    ),
                ],
            ])
            ->add('author', TextType::class, [
                'label' => 'Auteur',
                'attr' => [
                    'placeholder' => 'Nom de l\'auteur',
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new Assert\NotBlank(message: 'L\'auteur est obligatoire'),
                    new Assert\Length(
                        min: 2,
                        max: 255,
                        minMessage: 'Le nom de l\'auteur doit contenir au moins {{ limit }} caractères',
                        maxMessage: 'Le nom de l\'auteur ne peut pas dépasser {{ limit }} caractères'
                    ),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
        ]);
    }
}
