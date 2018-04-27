<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
                    'mel',
                     EmailType::class,
                    [
                        'label' => 'Email',
                        'attr' => [
                            'class' => 'perso'
                        ]
                    ]
                )
                ->add(
                     'telephone',
                     NumberType::class,
                        [
                            'label' => 'Téléphone',
                            'attr' => [
                                'class' => 'perso'
                            ]
                        ]
                )
                ->add('message',
                    TextareaType::class,
                    [
                        'label' => 'Message',
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
            'data_class' => Contact::class,
        ]);
    }
}
