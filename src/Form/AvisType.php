<?php

namespace App\Form;

use App\Entity\Avis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Entity\MarketPlace;
use Symfony\Component\Validator\Constraints\NotBlank;

class AvisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('DateAvis')
            ->add('Note')
           /* ->add('MarketPlace', EntityType::class, [
                'class' => MarketPlace::class,
                'multiple' => false, // Assuming it's a ManyToMany relationship
                'expanded' => false, // Display as checkboxes or a select dropdown
                'choice_label' => 'ProdName', // Change to the appropriate property
                'label' => 'Products',
                'constraints' => [
                    new NotBlank(['message' => 'Please select at least one Product.']),
                ],
            ])
           // ->add('MarketPlace')
*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Avis::class,
        ]);
    }
}
