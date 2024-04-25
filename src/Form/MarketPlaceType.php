<?php

namespace App\Form;

use App\Entity\MarketPlace;
use App\Entity\Avis;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class MarketPlaceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Price')
            ->add('Quantity')
            ->add('ProdName')
            ->add('ProdDescription')
            ->add('DateProd')
            ->add('Image', FileType::class, [
                'label' => 'Image',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please upload an image for the marketplace.',
                    ]),
                ],
            ])
           /* ->add('Avis', EntityType::class, [
                'class' => Avis::class,
                'multiple' => false, // Assuming it's a ManyToMany relationship
                'expanded' => false, // Display as checkboxes or a select dropdown
                'choice_label' => 'note', // Change to the appropriate property
                'label' => 'notes',
            ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MarketPlace::class,
        ]);
    }
}
