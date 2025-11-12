<?php

declare(strict_types=1);

namespace App\Modules\Comment\Presentation\Forms;

use App\Modules\Comment\Presentation\WriteModel\CommentModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class CommentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextareaType::class, [
                'label' => ' ',
                'attr' => [
                    'rows' => 3,
                    'placeholder' => 'Write your comment here...'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CommentModel::class,
        ]);
    }
}
