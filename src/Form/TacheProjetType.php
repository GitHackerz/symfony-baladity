<?php

namespace App\Form;

use App\Entity\TacheProjet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TacheProjetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', null, [
                'label' => 'Titre Tache',
            ])
            ->add('description', null, [
                'label' => 'Description Tache',
            ])
            ->add('date', null, [
                'label' => 'Deadline Tache',
                'widget' => 'single_text',
            ])
            ->add('projet', null, [
                'choice_label' => 'titre',
            ])
            ->add('user', null, [
                'choice_label' => function ($user) {
                    return $user->getCitoyen()->getNom() . ' ' . $user->getCitoyen()->getPrenom();
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TacheProjet::class,
        ]);
    }
}
