<?php
namespace App\Form;

use App\Entity\Equipe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class EquipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('image', FileType::class, [
                'label' => false,
                'mapped' => false,
                'required' => false, // Make the image field optional
                'attr' => ['accept' => 'image/*'],
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image (jpeg or png)',
                        'groups' => ['equipe_validation'], // Define validation group
                    ]),
                    new NotBlank([
                        'message' => 'Please upload an image file',
                        'groups' => ['equipe_validation'], // Define validation group
                    ]),
                ],
            ])
            ->add('dateCreation', DateTimeType::class, [
                'label' => 'Date Creation',
                'widget' => 'single_text', // Use a single text input for simplicity
                // You can add more options like 'required' if necessary
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Save',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Equipe::class,
            'validation_groups' => ['Default', 'equipe_validation'], // Apply validation group
        ]);
    }
}
