<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Votre nom est requis'
                    ]),
                    new Length([
                        'max' => 50,
                        'maxMessage' => 'Votre nom ne peut dépasser les {{ limit }} caractères'
                    ])
                ]
            ])
            ->add('email', EmailType::class, [
                'required' => false,
                'label' => 'Adresse email',
                'constraints' => [
                    new NotBlank([
                        'message' => "L'adresse email est requise"
                    ]),
                    new Email([
                        'message' => "L'adresse email est invalide"
                    ])
                ]
            ])
            ->add('avatarFile', FileType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Photo de profil',
                'help' => 'Votre photo de profil ne doit pas dépasser les 1Mo et doit être de type : PNG, WEBP ou JPG',
                'constraints' => [
                    new File([
                        'extensions' => ['png', 'jpeg', 'jpg', 'webp'],
                        'extensionsMessage' => "Votre fichier n'est pas une image autorisée",
                        'maxSize' => '1M',
                        'maxSizeMessage' => "L'image ne doit pas dépasser {{ limit }} en poids"
                    ])
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider'
            ])
        ;

        /**
         * Si la clé "is_profile" est à false, alors il s'agit du formulaire d'inscription, donc on ajoute
         * à notre formulaire, le champ "mot de passe" et l'acceptation des conditions
         */
        if (!$options['is_profile']) {
            $builder
                ->add('plainPassword', PasswordType::class, [
                    'mapped' => false, // n'existe pas dans l'entité user
                    'label' => 'Mot de passe',
                    'help' => 'Le mot de passe doit contenir 6 caractères au minimum',
                    'attr' => ['autocomplete' => 'new-password'],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Le mot de passe est requis',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Le mot de passe doit contenir {{ limit }} caractères minimum',
                            'max' => 4096, // max length allowed by Symfony for security reasons
                        ]),
                    ],
                ])
                ->add('agreeTerms', CheckboxType::class, [
                    'label' => "J'accepte les conditions générales d'utilisation",
                    'mapped' => false,
                    'constraints' => [
                        new IsTrue([
                            'message' => 'Vous devez accepter les conditions pour vous inscrire',
                        ]),
                    ],
                ])
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_profile' => false
        ]);
    }
}