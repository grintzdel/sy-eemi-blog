<?php

declare(strict_types=1);

namespace App\Modules\Article\Presentation\Forms;

use App\Modules\Article\Presentation\WriteModel\ArticleModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ArticleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isEdit = $options['is_edit'] ?? false;

        $builder
            ->add('heading', TextType::class)
            ->add('subheading', TextType::class)
            ->add('content', TextareaType::class)
            ->add('coverImage', FileType::class, [
                'required' => !$isEdit,
                'label' => $isEdit ? 'Cover Image (optional - leave empty to keep current)' : 'Cover Image',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ArticleModel::class,
            'is_edit' => false,
        ]);
    }
}
