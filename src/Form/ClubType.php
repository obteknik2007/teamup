<?php

namespace App\Form;

use App\Entity\Club;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClubType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                  'assoc',
                  TextType::class,
                    [
                        'label' => "N° d'association :",
                        'attr' => [
                            'class' => 'perso'
                        ]
                    ]
                )                
            ->add(
                  'nom',
                  TextType::class,
                    [
                        'label' => 'Nom :',
                        'attr' => [
                            'class' => 'perso'
                    ]
                ]
                )
            ->add(
                  'anneeCreation',
                  TextType::class,
                    [
                        'label' => 'Année de création :',
                        'attr' => [
                            'class' => 'perso'
                    ]]
                )
                
            ->add(
                  'sigle',
                  TextType::class,
                    [
                        'label' => 'Sigle Club :',
                        'attr' => [
                            'class' => 'perso'
                    ]]
                )
                
            ->add(
                  'couleurs',
                  TextType::class,
                    [
                        'label' => 'Couleurs du Club :',
                        'attr' => [
                            'class' => 'perso'
                    ]]
                )
                
            ->add(
                  'logo',
                  FileType::class,
                    [
                        'label' => 'Logo du Club :',
                        'attr' => [
                                    'id' => 'toggle'
                                   ],
                        'required' => false,
                        'attr' => [
                            'class' => 'well'
                        ]
                    ]
                )             
            ->add(
                  'adresseStade',
                  TextType::class,
                    [
                        'label' => 'Adresse du stade :',
                        'attr' => [
                            'class' => 'perso'
                    ]]
                )
                    
            ->add(
                  'emailBureau',
                  TextType::class,
                    [
                        'label' => 'Email du bureau:',
                        'attr' => [
                            'class' => 'perso'
                    ]]
                )
                
            ->add(
                  'telephoneBureau',
                  TextType::class,
                    [
                        'label' => 'Téléphone du bureau :',
                        'attr' => [
                            'class' => 'perso'
                    ]]
                )
                
        ;
        
         //$options['data'] = L'entité Club
        // si il y a une image enregistrée en bdd
        if (!is_null($options['data']->getLogo())) {
            $builder->add(
                    'remove_image',
                    CheckboxType::class,
                    [
                        'label' => "Supprimer l'illustration",
                        'required' => false,
                        //champ non relié à un attribut de l'entité Club
                        'mapped' => false
                    ]
            );
        }
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
