<?php

namespace App\Form;

use App\Entity\Equipe;
use App\Entity\Reservation;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use App\Entity\Stade;
use Doctrine\ORM\EntityManagerInterface;

class ReservationType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('idstade', HiddenType::class)
            ->add('date')
            ->add('idDeuxiemeequipe', EntityType::class, [
                'class' => Equipe::class,
                'choice_label' => 'nom'
            ])
            ->add('idOrganisateur', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'firstname'
            ])



            ->add('idPremiereequipe', EntityType::class, [
                'class' => Equipe::class,
                'choice_label' => 'nom'
            ]);

        $entityManager = $this->entityManager;

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($entityManager) {
            $data = $event->getData();
            if (!empty($data['idstade'])) {
                $stade = $entityManager->getRepository(Stade::class)->find($data['idstade']);
                $data['idstade'] = $stade;
                $event->setData($data);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
