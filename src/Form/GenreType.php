<?php

namespace App\Form;

use App\Entity\Genre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class GenreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Genre',
                'attr' => [
                    'placeholder' => 'Saisissez le Genre de Musique'
                    ]
            ])
            ->add('picture', FileType::class, array('data_class' => null), [
                'label' => 'Image',
                'required' => 'false',
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
            ->add('status', ChoiceType::class, [
                'choices'  => [
                    'Compte Activé' => '1',
                    'Compte Désactivé' => '2',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Genre::class,
            // delete client-side validation
            'attr' => ['novalidate' => 'novalidate']
        ]);
    }
}
