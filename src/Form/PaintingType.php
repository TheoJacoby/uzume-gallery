<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Painting;
use App\Entity\Technique;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Vich\UploaderBundle\Form\Type\VichImageType;

class PaintingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(['message' => 'Le titre est obligatoire']),
                    new Length([
                        'min' => 5,
                        'minMessage' => 'Le titre doit contenir au moins {{ limit }} caractères',
                        'max' => 255,
                    ]),
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => ['class' => 'form-control', 'rows' => 5],
                'constraints' => [
                    new NotBlank(['message' => 'La description est obligatoire']),
                ],
            ])
            ->add('created', DateTimeType::class, [
                'label' => 'Date de création',
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('height', NumberType::class, [
                'label' => 'Hauteur (cm)',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(['message' => 'La hauteur est obligatoire']),
                    new Positive(['message' => 'La hauteur doit être positive']),
                ],
            ])
            ->add('width', NumberType::class, [
                'label' => 'Largeur (cm)',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(['message' => 'La largeur est obligatoire']),
                    new Positive(['message' => 'La largeur doit être positive']),
                ],
            ])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image',
                'required' => false,
                'allow_delete' => false,
                'download_uri' => false,
                'attr' => [
                    'class' => 'form-control',
                    'accept' => 'image/jpeg,image/jpg,image/png',
                ],
            ])
            ->add('isPublished', CheckboxType::class, [
                'label' => 'Publié',
                'required' => false,
                'attr' => ['class' => 'form-check-input'],
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'label' => 'Catégorie',
                'attr' => ['class' => 'form-select'],
            ])
            ->add('techniques', EntityType::class, [
                'class' => Technique::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'label' => 'Techniques',
                'attr' => ['class' => 'form-check'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Painting::class,
        ]);
    }
}

