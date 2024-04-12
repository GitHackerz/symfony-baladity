<?php

namespace App\Form;

use App\Entity\Association;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Regex;





class AssociationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('nom', TextType::class, [
            'label' => 'Nom de l\'association',
            'attr' => ['class' => 'form-control'],
            'constraints' => [
                new NotBlank(),
                new Length(['max' => 255]),
            ]
        ])
            ->add('adresse', TextType::class, [
                'label' => 'Adresse',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 255]),
                ]
            ])
            ->add('caisse', NumberType::class, [
                'label' => 'Caisse',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(),
                    new Regex([
                        'pattern' => '/^[1-9]\d*(\.\d+)?$/',
                        'message' => 'Le montant de la caisse doit être un nombre valide et positif.',
                    ]),
                ]
            ])
            
            ->add('type', ChoiceType::class, [
                'label' => 'Type',
                'placeholder' => 'Choisissez',
                'choices' => [
                    'Sportive' => 'Sportive',
                    'Culturelle' => 'Culturelle',
                    'Académique' => 'Académique',
                    'Religieuse' => 'Religieuse',
                    'Professionnelle' => 'Professionnelle',
                ],
                'attr' => ['class' => 'form-select'],
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('statut', ChoiceType::class, [
                'label' => 'Statut',
                'placeholder' => 'Choisissez',
                'choices' => [
                    'Actif' => 'Actif',
                    'Inactif' => 'Inactif',
                ],
                'attr' => ['class' => 'form-select'],
                'constraints' => [
                    new NotBlank(),
                ]







            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Association::class,
        ]);
    }
}
