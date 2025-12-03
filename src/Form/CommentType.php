<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextareaType::class, [
                'label' => 'Votre commentaire',
                'attr' => [
                    'rows' => 5,
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Le commentaire est obligatoire']),
                    new Length([
                        'min' => 10,
                        'minMessage' => 'Le commentaire doit contenir au moins {{ limit }} caractères',
                        'max' => 1000,
                    ]),
                ],
            ])
            ->add('rating', IntegerType::class, [
                'label' => 'Note (1-5)',
                'attr' => [
                    'min' => 1,
                    'max' => 5,
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new Range([
                        'min' => 1,
                        'max' => 5,
                        'notInRangeMessage' => 'La note doit être entre {{ min }} et {{ max }}',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}

