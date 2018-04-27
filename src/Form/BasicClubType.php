<?php

namespace App\Form;

use App\Entity\Club;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BasicClubType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                  'nom',
                  TextType::class,
                    [
                        'label' => 'Nom du club',
                        'attr' => [
                            'class' => 'perso'
                        ]
                    ]
                )
            ->add(
                  'assoc',
                  TextType::class,
                    [
                        'label' => 'N° association',
                        'attr' => [
                            'class' => 'perso'
                        ]
                    ]
                )
                
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([    
            'data_class' => Club::class,    
            // uncomment if you want to bind to a class
            //'data_class' => Club::class,
        ]);
    }
}
