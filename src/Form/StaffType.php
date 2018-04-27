<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StaffType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
            'prenom',
                    TextType::class,
                    [
                        'label' => 'Prénom',
                        'attr' => [
                            'class' => 'perso'
                        ]
                    ]        
            )
            ->add(
            'nom',
                    TextType::class,
                    [
                        'label' => 'Nom',
                        'attr' => [
                            'class' => 'perso'
                        ]
                    ]        
            )
            ->add(
                'fonction',
                ChoiceType::class, array
                (
                    'label'=>"Fonction :",
                    'choices'=>array (
                    'Dirigeant' => 1,
                    'Entraineur' => 2),
            'choices_as_values' => true,'multiple'=>false,'expanded'=>true)                 
            ) 
            ->add('image',
                    //input type file
                    FileType::class,
                    [
                        'label' => 'Choisissez une photo',
                        'required' => false
                    ]
            )         
        ;
        
                                        //$options['data'] = L'entité Article
        if(!is_null($options['data']->getImage())){
            $builder->add(
               'remove_image',
               CheckboxType::class,
               [
                   'label' => "Supprimer l'illustration",
                   'required' => false,
                   //champ non relié à un attribut de l'entité Article
                   'mapped' => false
               ]
            );
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // uncomment if you want to bind to a class
            //'data_class' => Staff::class,
        ]);
    }
}
