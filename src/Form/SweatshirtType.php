<?php

namespace App\Form;

use App\Entity\Sweatshirt;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use symfony\Component\Validator\Constraints\File;

class SweatshirtType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du Sweatshirt',
            ])
            ->add('price', NumberType::class, [
                'label' => 'Prix',
                'required' => true,
            ])
            ->add('stockXS', NumberType::class,[
                'label' => 'Stock XS',
            ])
            ->add('stockS', NumberType::class,[
                'label' => 'Stock S',
            ])
            ->add('stockM', NumberType::class,[
                'label' => 'Stock M'
            ])
            ->add('stockL', NumberType::class,[
                'label' => 'Stock L',
            ])
            ->add('stockXL', NumberType::class,[
                'label' => 'Stock XL',
            ])
            ->add('imageName', FileType::class,[
                'label' => 'Image du Sweatshirt (PNG, JPG, JPEG)',
                'required' => false,
                'mapped' =>false,
                'constraints' => [
                    new File (['maxSize' => '5M',
                    'mimeTypes' => ['image/jpeg', 'image/png', 'image/jpeg'],
                    'mimeTypesMessage' => 'Veuillez uploader une image au format JPG ou PNG ou JPEG.',
                    ])
                ],
            ])
            ->add('isFeatured', CheckboxType::class, [
                'required' => 'false',
                'label' => 'Mettre en avant',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sweatshirt::class,
        ]);
    }
}
