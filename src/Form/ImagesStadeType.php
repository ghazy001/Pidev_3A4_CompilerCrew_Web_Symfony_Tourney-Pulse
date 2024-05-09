<?php
namespace App\Form;

use App\Entity\ImagesStade;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImagesStadeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('urlImage', FileType::class, [
                'label' => 'Stade Image(s)',
                'mapped' => false, // This field does not directly map to the entity property
                'required' => false,
                'multiple' => true, // Allow multiple file selection
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ImagesStade::class,
        ]);
    }
}
