<?php

namespace App\Form;

use App\Entity\Equipe;
use App\Entity\Rencontre;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class RencontreType extends AbstractType
{
    
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage) {
        $this->tokenStorage = $tokenStorage;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $club = $this->tokenStorage->getToken()->getUser()->getClub();
        
        $builder
            ->add('date', DateType::class, array(
              'widget' => 'single_text',
                    'attr' => [
                            'class' => 'perso'
                        ]      
            ))
                
             ->add('equipe1',
                  //<select> sur une équipe
                  EntityType::class,
                  [
                      'label' => 'Associé à l\'équipe',
                      'class' => Equipe::class,
                      'query_builder' => function (EntityRepository $er) use ($club) {
                            return $er->createQueryBuilder('e')
                                    ->where('e.local = :local')
                                    ->setParameter('local',1)
                                    ->andWhere('IDENTITY(e.club) = :club')
                                    ->setParameter('club', $club->getId())
                            ;
                        },
                      //nom du champ qui s'affiche dans les <option>
                      'choice_label' => 'nom',
                      //1ère option vide
                      'placeholder' => 'Choisissez une équipe du club',
                       'attr' => [
                            'class' => 'perso'
                        ]             
                  ]  
                ) 
            ->add('equipe2',
                  //<select> sur une équipe
                  EntityType::class,
                  [
                      'label' => 'Associé à l\'équipe',
                      'class' => Equipe::class,
                      'query_builder' => function (EntityRepository $er) use ($club) {
                            return $er->createQueryBuilder('e')
                                    ->where('e.local = :local')
                                    ->setParameter('local',0)
                                    ->andWhere('IDENTITY(e.club) = :club')
                                    ->setParameter('club', $club->getId())
                            ;
                        },
                      //nom du champ qui s'affiche dans les <option>
                      'choice_label' => 'nom',
                      //1ère option vide
                      'placeholder' => 'Choisissez une équipe extérieure',
                       'attr' => [
                            'class' => 'perso'
                        ]             
                  ]  
                ) 
            ->add(
                'domicile',
                ChoiceType::class, array
                (
                    'label'=>"A domicile/En extérieur",
                    'choices'=>array (
                    'Match à domicile' => true,
                    'Match extérieur' => false),
            'choices_as_values' => true,'multiple'=>false,'expanded'=>true)                 
            )    
            ->add(
                  'lieu',
                    TextType::class,
                    [
                        'label' => 'Lieu de la rencontre',
                         'attr' => [
                            'class' => 'perso',
                        ]
                    ]
                )
            ;
            if ($options['data']->getId()) {
                $builder->add(
                      'equipe1Score',
                      IntegerType::class,
                        [
                            'label' => "Score de l'équipe 1",
                             'attr' => [
                                'class' => 'perso'
                            ]             
                        ]
                    )   
                ->add(
                      'equipe2Score',
                      IntegerType::class,
                        [
                            'label' => "Score de l'équipe 2",
                             'attr' => [
                                'class' => 'perso'
                            ]             
                        ]
                    )  
                ;
            }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // uncomment if you want to bind to a class
            'data_class' => Rencontre::class,
        ]);
    }
}
