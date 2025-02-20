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
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Form\Type\VichImageType;

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
            ->add('imageFile', VichImageType::class,[
                'label' => 'Image du Sweatshirt',
                'label_attr' =>  [
                    'class' => 'form-label mt-4'
                ],
            ])
            ->add('isFeatured', CheckboxType::class, [
                'attr' => [
                    'class' => 'form-check-input',
                ],
                'required' => false, //n'est pas obligatoire
                'label' => 'Mettre en avant ?',
                'label_attr' => [
                'class' => 'form-check-label'
            ],
            'constraints' => [
                new Assert\NotNull() //Empêche une valeurs null (doit etre tree ou false)
            ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sweatshirt::class,
        ]);
    }
}
