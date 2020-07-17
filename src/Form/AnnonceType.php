<?php

namespace App\Form;

use App\Entity\Annonce;
use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;

class AnnonceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'nom',
                "attr" => [
                    "class" => "form-control"
                ]
            ])
            ->add('titre', TextType::class, [
                "constraints" => [
                    new Length([
                        "min" => 5,
                        "max" => 50,
                        "minMessage" => "Le titre doit comporter un minimum {{ limit }} caractères",
                        "maxMessage" => "Le titre ne doit pas dépasser {{ limit }} caractères"
                    ])
                ],
                "attr" => [
                    "class" => "form-control"
                ]
            ])
            ->add('description', TextareaType::class, [
                "constraints" => [
                    new Length([
                        "min" => 0,
                        "max" => 500,
                        "minMessage" => "Le nom doit comporter un minimum {{ limit }} caractères",
                        "maxMessage" => "Le nom ne doit pas dépasser {{ limit }} caractères"
                    ])
                ],
                "attr" => [
                    "class" => "form-control"
                ]
            ])
            ->add('adresse', TextareaType::class, [
                "constraints" => [
                    new Length([
                        "max" => 255, 
                        "maxMessage" => "Le prénom ne doit passer dépasser {{ limit }} caractères"
                    ])
                ],
                "attr" => [
                    "class" => "form-control"
                ]

            ])
            ->add('code_postal',TextType::class, [
                "constraints" => [
                    new Regex([
                        "pattern" => "/^[0-9]{5}$/",
                        "message" => "Le code postal ne doit comporte que 5 chiffres"
                    ])
                ],
                "attr" => [
                    "class" => "form-control"
                ]
            ])
            ->add('ville', TextType::class, [
                "constraints" => [
                    new Length([
                        "min" => 2,
                        "max" => 50, 
                        "minMessage" => "La ville doit comporter au minimum {{ limit }} caractères",
                        "maxMessage" => "La ville ne doit passer dépasser {{ limit }} caractères"
                    ])
                ],
                "attr" => [
                    "class" => "form-control"
                ]
            ])
            ->add('prix', IntegerType::class, [
                "attr" => [
                    "class" => "form-control",
                    "min" => 1,
                ]
            ])
        ;
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }
}
