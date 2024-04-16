<?php

namespace App\Form;

use App\Entity\Association;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AssociationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de l\'association',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez le nom de l\'association'
                ],
            ])
            ->add('adresse', TextType::class, [
                'label' => 'Adresse',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez l\'adresse'
                ],
            ])
            ->add('caisse', NumberType::class, [
                'label' => 'Caisse',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez le montant de la caisse'
                ],
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
            ])
            ->add('statut', ChoiceType::class, [
                'label' => 'Statut',
                'placeholder' => 'Choisissez',
                'choices' => [
                    'Actif' => 'Actif',
                    'Inactif' => 'Inactif',
                ],
                'attr' => ['class' => 'form-select'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Association::class,
        ]);
    }
}
