<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Rencontre;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ArticleType extends AbstractType
{
        private $tokenStorage;

        public function __construct(TokenStorageInterface $tokenStorage) {
            $this->tokenStorage = $tokenStorage;
        }
        
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $club = $this->tokenStorage->getToken()->getUser()->getClub();

        $builder
                ->add('rencontre',
                  EntityType::class,
                  [
                      'label' => "Pour la rencontre :",
                      'class' => Rencontre::class,
                      //Req liste des rencontres du club
                      'query_builder' => function (EntityRepository $er) use ($club) {
                            return $er->createQueryBuilder('e')
                                    ->where('IDENTITY(e.club) = :club')
                                    ->setParameter('club', $club->getId())
                            ;
                        },
                      //nom du champ qui s'affiche dans les <option>
                      'choice_label' => "libelle",
                      //1ère option vide
                      'placeholder' => 'Choisissez la rencontre',
                       'attr' => [
                            'class' => 'perso'
                        ]             
                  ]  
                ) 
            ->add(
                'title',
                TextType::class,
                [
                    'label' => 'Titre',
                    'attr' => [
                    'class' => 'perso'
                    ]
                ]
            )
            ->add(
                'content',
                TextareaType::class,
                [
                    'label' => 'Contenu',
                    'attr' => [
                    'class' => 'perso'
                    ]
                ]
            )   
             ->add(
                'image',
                //input type file     
                FileType::class,
                [
                    'label' => 'Illustration',
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
            'data_class' => Article::class,
        ]);
    }
}
