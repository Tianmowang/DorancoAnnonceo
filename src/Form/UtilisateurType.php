<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                "label" => "E-mail",
                "attr" => [
                    "class" => "form-control"
                ]
            ])
            ->add('password', PasswordType::class, [
                "label" => "Mot de passe",
                /* "constraints" => [
                    new Regex([
                        "pattern" => "/^(?=.{6,10}$)(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$/",
                        "message" => "Le mot de passe doit comporter entre 6 et 10 caractères, une minuscule, une majuscule, un chiffre, un caractères spécial"
                    ])
                ], */
                "help" => "Le mot de passe doit comporter entre 6 et 10 caractères, une minuscule, une majuscule, un chiffre, un caractères spécial",
                "mapped" => false,
                "required" => false,
                "attr" => [
                    "class" => "form-control"
                ]
            ])
            ->add('pseudo', TextType::class, [
                "constraints" => [
                    new Length([
                        "min" => 5,
                        "max" => 20,
                        "minMessage" => "Le pseudo doit comporter un minimum {{ limit }} caractères",
                        "maxMessage" => "Le pseudo ne doit pas dépasser {{ limit }} caractères"
                    ])
                ],
                "attr" => [
                    "class" => "form-control"
                ]
            ])
            ->add('nom', TextType::class, [
                "constraints" => [
                    new Length([
                        "min" => 2,
                        "max" => 50,
                        "minMessage" => "Le nom doit comporter un minimum {{ limit }} caractères",
                        "maxMessage" => "Le nom ne doit pas dépasser {{ limit }} caractères"
                    ])
                ],
                "attr" => [
                    "class" => "form-control"
                ]
            ])
            ->add('prenom', TextType::class, [
                "constraints" => [
                    new Length([
                        "min" => 3,
                        "max" => 50,
                        "minMessage" => "Le prénom doit comporter un minimum {{ limit }} caractères",
                        "maxMessage" => "Le prénom ne doit pas dépasser {{ limit }} caractères"
                    ])
                ],
                "attr" => [
                    "class" => "form-control"
                ]
            ])
            ->add('telephone', TelType::class, [
                "constraints" => [
                    new Regex([
                        "pattern" => "/^[0-9]{10}$/",
                        "message" => "Le telephone ne doit comporter que 5 chiffres"
                    ])
                ],
                "attr" => [
                    "class" => "form-control"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
