<?php

namespace App\Form;

use App\Class\Contact;
use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaType;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\IsTrue as RecaptchaTrue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Votre prénom',
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Votre nom',
            ])
            ->add('email', EmailType::class, [
                'label' => 'Votre Email',
            ])
            ->add('subject', TextType::class, [
                'label' => 'Votre sujet',
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Votre message',
            ])
            ->add('recaptcha', EWZRecaptchaType::class, [
                'label' => false,
                'mapped' => false,
                'constraints' => [
                    new RecaptchaTrue([
                        'message' => 'Veuillez valider le reCAPTCHA',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}

