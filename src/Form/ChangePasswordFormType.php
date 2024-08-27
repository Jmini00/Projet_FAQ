<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChangePasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'options' => [
                    'attr' => [
                        'autocomplete' => 'new-password',
                    ],
                ],
                'first_options' => [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Le mot de passe est requis',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Le mot de passe doit contenir {{ limit }} caractères minimum',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                    ],
                    'label' => 'Nouveau mot de passe',
                ],
                'second_options' => [
                    'label' => 'Confirmer le nouveau mot de passe',
                ],
                'invalid_message' => 'Les mots de passe ne correspondent pas',
                // Instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider mon nouveau mot de passe'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
