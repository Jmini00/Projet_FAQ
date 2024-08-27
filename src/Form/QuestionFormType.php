<?php

namespace App\Form;

use App\Entity\Question;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class QuestionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Poser votre question',
                'constraints' => [
                    new NotBlank([
                        'message' => 'La question est requise'
                    ]),
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'Moins longue la question !!!'
                    ])
                ]
            ])
            ->add('contenu', TextareaType::class, [
                'required' => false,
                'label' => 'Précisez votre pensée',
                'attr' => ['rows' => 10],
                'constraints' => [
                    new Length([
                        'min' => 10,
                        'minMessage' => '{{ limit }} caractères au minimum'
                    ])
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Poster ma question'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}
