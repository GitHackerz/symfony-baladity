<?php

namespace App\Form;

use App\Entity\Projet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre Projet',
                'attr' => ['placeholder' => 'Titre Projet'],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description Projet',
                'attr' => ['placeholder' => 'Description Projet'],
            ])
            ->add('dateDebut', DateType::class, [
                'label' => 'Date Debut',
                'widget' => 'single_text',
            ])
            ->add('dateFin', DateType::class, [
                'label' => 'Date Fin',
                'widget' => 'single_text',
            ])
            ->add('budget', NumberType::class, [
                'label' => 'Budget',
                'attr' => ['placeholder' => 'Budget Projet'],
            ])
            ->add('statut', ChoiceType::class, [
                'label' => 'Statut Projet',
                'choices' => [
                    'Planned' => 'Planned',
                    'In Progress' => 'In Progress',
                    'Completed' => 'Completed',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Projet::class,
        ]);
    }
}
