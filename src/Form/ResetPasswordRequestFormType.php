<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class ResetPasswordRequestFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => ['autocomplete' => 'email'],
                'help' => "Ecrivez l'adresse email ayant servi à l'inscription",
                'constraints' => [
                    new NotBlank([
                        'message' => 'Votre adresse email est requise',
                    ]),
                    new Email([
                        'message' => 'Votre adresse email est invalide'
                    ])
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Recevoir l'email"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
