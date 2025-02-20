<?php

namespace App\Form; 
use App\Entity\User; 
use Symfony\Component\Form\AbstractType; 
use Symfony\Component\Form\Extension\Core\Type\CheckboxType; 
use Symfony\Component\Form\Extension\Core\Type\EmailType; 
use Symfony\Component\Form\Extension\Core\Type\PasswordType; 
use Symfony\Component\Form\Extension\Core\Type\TextType; 
use Symfony\Component\Form\FormBuilderInterface; 
use Symfony\Component\OptionsResolver\OptionsResolver; 
use Symfony\Component\Validator\Constraints\NotBlank; 
use Symfony\Component\Validator\Constraints\Length; 
use Symfony\Component\Validator\Constraints\IsTrue; 

class RegistrationFormType extends AbstractType 
{ 
    public function buildForm(FormBuilderInterface $builder, array $options): void 
    { 
        $builder 
        ->add('name', TextType::class, [ 
            'label' => 'Nom d\'utilisateur', 
            'constraints' => [ 
                new NotBlank([
                    'message' => 'Veuillez entrer un nom d\'utilisateur.'
                ]), 
            ], 
        ])     
        ->add('email', EmailType::class, [ 
            'label' => 'Adresse e-mail', 
            'constraints' => [ 
                new NotBlank([
                    'message' => 'Veuillez entrer une adresse e-mail.'
                ]), 
            ], 
        ]) 
        ->add('deliveryAddress', TextType::class, [ 
            'label' => 'Adresse de livraison', 
            'constraints' => [ 
                new NotBlank([
                    'message' => 'Veuillez entrer votre adresse de livraison.'
                ]), 
            ], 
        ]) 
        ->add('password', PasswordType::class, [ 
            'label' => 'Mot de passe', 
            'constraints' => [ 
                new NotBlank([
                    'message' => 'Veuillez entrer un mot de passe.'
                ]), 
                new Length([ 
                    'min' => 6, 
                    'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères.', 
                ]), 
            ], 
        ]) 
        ->add('confirmPassword', PasswordType::class, [ 
            'label' => 'Confirmez votre mot de passe',
            'constraints' => [ 
                new NotBlank([
                    'message' => 'Veuillez confirmer votre mot de passe.'
                ]), 
            ], 
        ]) 
        ->add('agreeTerms', CheckboxType::class, [ 
            'label' => 'J\'accepte les conditions', 
            'mapped' => false, 
            'constraints' => [ 
                new IsTrue([
                    'message' => 'Vous devez accepter les conditions.'
                ]), 
            ], 
        ]);
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

    

