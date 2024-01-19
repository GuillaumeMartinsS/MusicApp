<?php

namespace App\Form;

use App\Entity\Song;
use App\Entity\User;
use App\Entity\Playlist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PlaylistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la playlist',
                'attr' => [
                    'placeholder' => 'Saisissez le nom de votre playlist'
                    ]])
            ->add('picture', FileType::class, array('data_class' => null), [
                'label' => 'Placer ici l\'image de vote playlist',
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'image/*',
                        ],
                        'mimeTypesMessage' => 'Un fichier de type image est démandé',
                    ])],])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'placeholder' => 'Décrivez votre playlist'
                    ]])
            ->add('album', ChoiceType::class, [
                'choices'  => [
                    'Cette playlist est un album' => true,
                    'Cette playlist n\'est pas un album' => false,
                ],
            ])
            ->add('status', ChoiceType::class, [
                'choices'  => [
                    'Playlist Activée' => '1',
                    'Playlist Désactivée' => '2',
                ],
            ])
            ->add('user', EntityType::class,[
                'class' => User::class,                
                'choice_label' => 'name',
                'multiple' => false,
                'expanded' => false,])

            ->add('songs', EntityType::class,[
                'class' => Song::class,                
                'choice_label' => 'title',
                'multiple' => true,
                'expanded' => false,])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Playlist::class,
            // delete client-side validation
            'attr' => ['novalidate' => 'novalidate']
        ]);
    }
}
