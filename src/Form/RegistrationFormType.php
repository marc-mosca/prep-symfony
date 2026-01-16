<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                "required" => true,
                "label" => "Nom d'utilisateur",
                "attr" => ["placeholder" => "John Doe"],
            ])
            ->add('age', IntegerType::class, [
                "required" => true,
                "label" => "Age",
                "attr" => ["placeholder" => "18"],
            ])
            ->add('email', EmailType::class, [
                "required" => true,
                "label" => "Email",
                "attr" => ["placeholder" => "john@doe.fr"],
            ])
            ->add('password', RepeatedType::class, [
                "type" => PasswordType::class,
                "invalid_message" => "Les mots de passe ne correspondent pas",
                "required" => true,
                "first_options" => [
                    "label" => "Mot de passe",
                    "attr" => ["placeholder" => "********"]
                ],
                "second_options" => [
                    "label" => "Confirmer le mot de passe",
                    "attr" => ["placeholder" => "********"]
                ],
            ])
            ->add("submit", SubmitType::class, [
                "label" => "S'inscrire",
                "attr" => ["class" => "btn btn-neutral"]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
