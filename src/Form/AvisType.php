<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Avisjoueur;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class AvisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username'
            ])
            ->add('dateavis', DateType::class, [
                'widget' => 'single_text',
                'data' => new \DateTime(), // Set current date as default
                'attr' => ['readonly' => true], // Make it read-only
            ])
            ->add('note')

            ->add('commentaire', TextareaType::class) // Transform commentaire to textarea
            ->add('submit', SubmitType::class, [
                'label' => 'Save',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Avisjoueur::class,
        ]);
    }
}
// Transform commentaire to textarea
// Transform commentaire to textarea
// Transform commentaire to textarea
// Transform commentaire to textarea

// Transform commentaire to textarea

// Transform commentaire to textarea

// Transform commentaire to textarea

// Transform commentaire to textarea

// Transform commentaire to textarea

// Transform commentaire to textarea

// Transform commentaire to textarea

// Transform commentaire to textarea

// Transform commentaire to textarea

