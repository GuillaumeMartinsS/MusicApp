<?php

namespace App\Form;

use App\Entity\Song;
use App\Entity\User;
use App\Entity\Genre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class SongType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('title', TextType::class, [
            'label' => 'Titre',
            'attr' => [
                'placeholder' => 'Saisissez le titre de votre chanson'
                ]])

            //! voir mettre de vraies contraintes
            ->add('file', FileType::class, array('data_class' => null), [
                'label' => 'Placer ici votre fichier musical',
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'audio/*',
                        ],
                        'mimeTypesMessage' => 'Un fichier de type musique est démandé',
                    ])]
                ])
            ->add('picture', FileType::class, array('data_class' => null), [
                'label' => 'Placer ici la pochette de votre musique',
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'image/*',
                        ],
                        'mimeTypesMessage' => 'Un fichier de type image est démandé',
                    ])]
                ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'placeholder' => 'Décrivez votre chanson'
                    ]])
            ->add('status', ChoiceType::class, [
                'choices'  => [
                    'Musique Activée' => '1',
                    'Musique Désactivée' => '2',
                ],
            ])
            ->add('releaseDate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de sortie de votre chanson',
                'input' => 'datetime'])

                //! ajout obligatoire de releaseDate
            // ->add('nbLike')
            // ->add('nbListened')
            // ->add('slug')
            //  ->add('users', ChoiceType::class,[
            //     'choices' => New User([
            //     'name' => 5,

            //     ]),
            //     'multiple' => true,
            //     'expanded' => true])
            ->add('users', EntityType::class,[
                'class' => User::class,                
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,])
            // ->add('users', EntityType::class,[
            //     'class' => User::class,
            //     'choice_name' => ChoiceList::fieldName($this, 'name'),
            //     'choices' => '$user->getName()',
            //     ])
            // ->add('playlists')
            ->add('genres', EntityType::class,[
                'class' => Genre::class,                
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Song::class,
            // delete client-side validation
            'attr' => ['novalidate' => 'novalidate']
        ]);
    }
}
