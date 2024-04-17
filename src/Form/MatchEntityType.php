<?php

namespace App\Form;

use App\Entity\Equipe;
use App\Entity\MatchEntity;
use App\Entity\Tournois;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class MatchEntityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomMatch')
            ->add('dateMatch')
            ->add('dureeMatch')
            ->add('idEquipe1', EntityType::class, [
                'class' => Equipe::class,
                'choice_label' => 'nom',
            ])
            ->add('idEquipe2', EntityType::class, [
                'class' => Equipe::class,
                'choice_label' => 'nom',
            ])
            ->add('idTournois', EntityType::class, [
                'class' => Tournois::class,
                'choice_label' => 'nomTournois',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MatchEntity::class,
        ]);
    }
}
