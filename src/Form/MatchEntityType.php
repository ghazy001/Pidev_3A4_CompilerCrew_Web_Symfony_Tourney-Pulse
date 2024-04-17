<?php

namespace App\Form;

use App\Entity\MatchEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MatchEntityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomMatch')
            ->add('dateMatch')
            ->add('dureeMatch')
            ->add('idEquipe2')
            ->add('idEquipe1')
            ->add('idTournois')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MatchEntity::class,
        ]);
    }
}
