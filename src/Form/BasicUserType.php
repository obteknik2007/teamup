<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BasicUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder        
                ->add(
                    'email',
                     EmailType::class,
                    [
                        'label' => 'Email',
                        'attr' => [
                            'class' => 'perso'
                        ]
                    ]
                )
                //le mdp en clair que l'on ne va pas stocker en bdd
                ->add(
                     'plainPassword',
                        
                        //2 champs qui doivent etre identiques
                     RepeatedType::class,
                    [
                        //... de type password
                        'type' => PasswordType::class,
                        'first_options' => [
                            'label' => 'Mot de passe',
                            'attr' => [
                            'class' => 'perso'
                        ]
                        ],
                        'second_options' => [
                            'label' => 'Confirmation du mot de passe',
                            'attr' => [
                            'class' => 'perso'
                        ]
                        ]
                    ]
                )
                
                
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
