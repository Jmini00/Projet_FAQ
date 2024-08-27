<?php

namespace App\Form;

use App\Entity\Question;
use App\Entity\Reponse;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ReponseFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('contenu', TextareaType::class, [
                'label' => 'Votre réponse',
                'attr' => ['rows' => 8],
                'constraints' => [
                    new NotBlank([
                        'message' => 'La réponse est requise'
                    ]),
                    new Length([
                        'min' => 10,
                        'minMessage' => 'La réponse doit contenir au minimum {{ limit }} caractères'
                    ])
                ]
            ])  
            ->add('submit', SubmitType::class, [
                'label' => 'Poster ma réponse'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reponse::class,
        ]);
    }
}
