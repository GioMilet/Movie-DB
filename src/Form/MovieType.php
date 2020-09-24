<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Movie;
use App\Entity\Person;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class MovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'title', 
            TextType::class,
            [
                "label" => "Titre du film"
            ]
            );
            $builder->add(
                'releaseDate', 
                DateType::class,
                [
                    'widget' => 'single_text', "label" => "Date de sortie:"
                ]
            );
            $builder->add(
                'director', EntityType::class, [
                'class'=> Person::class,
                'placeholder'=>'Réalisateur du film',
                'choice_label'=>'name',
                     "label" => "Réalisateur:"
                ]
            );

            $builder->add('writers', EntityType::class, [
                'multiple'=>true,
                'class' => Person::class,
                'choice_label' => 'name'
            ]);

            $builder->add('categories', EntityType::class, [
                'multiple'=>true,
                'class' => Category::class,
                'choice_label' => 'label'
            ]);
           $builder->add('image', FileType::class, [
                'label' => 'Affiche du film',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/*',
                        ],
                    ])
                ],
            ]);
            $builder->add('movieActors', CollectionType::class, [
                'entry_type' => MovieActorType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                "by_reference" => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }
}
