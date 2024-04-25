<?php

namespace App\Form;

use App\Entity\Citoyen;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CitoyenType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
    ->add('cin', null, ['attr' => ['class' => 'form-control'], 'label' => 'CIN'])
    ->add('nom', null, ['attr' => ['class' => 'form-control'], 'label' => 'Nom'])
    ->add('prenom', null, ['attr' => ['class' => 'form-control'], 'label' => 'Prénom'])
    ->add('adresse', null, ['attr' => ['class' => 'form-control'], 'label' => 'Adresse'])
    ->add('dateNaissance', DateType::class, [
        'widget' => 'single_text',
        'attr' => ['class' => 'form-control'],
        'label' => 'Date de Naissance'
    ])
    ->add('genre', ChoiceType::class, [
        'choices' => [
            'Homme' => 'homme',
            'Femme' => 'femme',
        ],
        'attr' => ['class' => 'form-control'],
        'label' => 'Genre'
    ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Citoyen::class,
        ]);
    }
}
