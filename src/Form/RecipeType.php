<?php

namespace App\Form;

use App\Entity\Recipe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add(
                'shortdescription',
                TextareaType::class,
                [
                    'label' => 'Kurze Beschreibung',
                    'attr' => ['rows' => 2, 'class' => 'textarea-resize-none'],
                ]
            )
            ->add(
                'preparation',
                TextareaType::class,
                [
                    'label' => 'Zubereitung',
                    'attr' => ['rows' => 10, 'class' => 'textarea-resize-none'],
                ]
            )
            ->add(
                'picture',
                FileType::class,
                [
                    'label' => 'Bild',
                    'mapped' => false,
                    'required' => false,
                ]
            )
            ->add('removepicture', CheckboxType::class, [
                'required' => false,
                'mapped' => false,
                'row_attr' => ['class' => 'd-none',]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
