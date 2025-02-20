<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType; 
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType; 

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class, [ 
            'label' => 'Nom d\'utilisateur',
            'attr' => ['class' => 'form-control']
        ])
        ->add('password', PasswordType::class, [ 
            'label' => 'Mot de passe',
            'attr' => ['class' => 'form-control']
        ])  
        ->add('submit', SubmitType::class, [ 
            'label' => 'Se connecter',
            'attr' => ['class' => 'btn btn-primary']
        ])       
    
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
