<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class, [
            'label' => 'Pseudo',
            'attr' => [
                'placeholder' => 'Saisissez votre pseudo'
                ]
        ])
        ->add('email', EmailType::class, [
            'label' => 'E-Mail',
            'attr' => [
                'placeholder' => 'Saisissez votre e-mail'
                ]
        ])
        //  ->add('password', PasswordType::class, [
        //      'empty_data' => '',
        //      'label' => 'Password',
        //      'attr' => [
        //          'placeholder' => 'Saisissez votre mot de passe'
        //          ]       
        // ])
        ->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
            // getting the form to work on it
            $form = $event->getForm();
            // getting the mapped user on the form from the event
            $user = $event->getData();
            // dd($user);

            // we make conditions for "passeword" field
            // if user exists, then it gets an id (so =/= null)
            if ($user->getId() !== null) {
                // Edit
                $form->add('password', PasswordType::class, [
                    'mapped' => false,
                    'label' => 'Password',
                    'attr' => [
                        'placeholder' => 'Laissez vide si inchangé'
                    ]
                ]);
                // dd($form);
            } else {
                // New
                $form->add('password', PasswordType::class, [
                    'empty_data' => '',
                    'constraints' => [
                        new NotBlank(),
                    ],
                    'label' => 'Password',
                    'attr' => [
                        'placeholder' => 'Saisissez votre mot de passe'
                    ]  
                ]);
            }
        })
        ->add('picture', FileType::class, array('data_class' => null), [
            // 'mapped' => false,
            'label' => 'Image',
            'constraints' => [
                new File([
                    'mimeTypes' => [
                        'image/*',
                    ],
                    'mimeTypesMessage' => 'Un fichier de type image est démandé',
                ])],    
        ])
        ->add('description', TextareaType::class, [
            'label' => 'Description',
            'attr' => [
                'placeholder' => 'Saisissez une description'
                ]     
        ])
        ->add('roles', ChoiceType::class, [
            'choices'  => [
                'Utilisateur' => 'ROLE_USER',
                'Administrateur' => 'ROLE_ADMIN',
            ],
            'multiple' => true,
            'expanded' => true,
        ])
        ->add('status', ChoiceType::class, [
            'choices'  => [
                'Compte Activé' => '1',
                'Compte Désactivé' => '2',
            ],
        ])
        ->add('certification', ChoiceType::class, [
            'choices'  => [
                'Compte Certifié' => true,
                'Compte Non Certifié' => false,
            ],
        ])
        ->add('label', TextType::class, [
            'label' => 'Label',
            'attr' => [
                'placeholder' => 'Saisissez votre Label si vous en avez un'
                ]
        ])
        // ->add('songs')
        // ->add('subscribers')
        // ->add('subscriptions')
    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            // delete client-side validation
            'attr' => ['novalidate' => 'novalidate']
        ]);
    }
}
