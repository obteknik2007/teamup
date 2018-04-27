<?php

namespace App\Form;

use App\Entity\Equipe;
use App\Entity\Staff;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class EquipeType extends AbstractType
{
    private $tokenStorage;

        public function __construct(TokenStorageInterface $tokenStorage) {
            $this->tokenStorage = $tokenStorage;
        }
        
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       
        $club = $this->tokenStorage->getToken()->getUser()->getClub();
        
        $builder
            ->add(
                'local',
                ChoiceType::class, array
                (
                    'label'=>"Type d'équipe",
                    'choices'=>array (
                    'Equipe du club' => true,
                    'Equipe extérieure' => false),
            'choices_as_values' => true,'multiple'=>false,'expanded'=>true)                 
            )               
            ->add(
                'nom',
                    TextType::class,
                    [
                        'label' => 'Nom ( ex: U10 , U17...)',
                        'attr' => [
                            'class' => 'perso'
                        ]
                    ]
                )
            ->add('image',
                    //input type file
                    FileType::class,
                    [
                        'label' => 'Choisissez une photo de votre équipe',
                        'required' => false
                    ]
            )
            ->add('staff',
                  EntityType::class,
                  [
                      'label' => "Associée à l'entraîneur",
                      'class' => Staff::class,
                      'query_builder' => function (EntityRepository $er) use ($club) {
                            return $er->createQueryBuilder('e')
                                    ->where('e.fonction = :fonction')
                                    ->setParameter('fonction',2)
                                    ->andWhere('IDENTITY(e.club) = :club')
                                    ->setParameter('club', $club->getId())
                            ;
                        },
                      //nom du champ qui s'affiche dans les <option>
                      'choice_label' => 'nom',
                      //1ère option vide
                      'placeholder' => 'Choisissez un entraîneur',
                       'attr' => [
                            'class' => 'perso'
                        ]             
                  ]  
                ) 
        ;
        
        if (!is_null($options['data']->getImage())){
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
            'data_class' => Equipe::class,
        ]);
    }
}
