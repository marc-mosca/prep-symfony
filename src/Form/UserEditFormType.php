<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserEditFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                "required" => true,
                "label" => "Nom d'utilisateur",
            ])
            ->add('email', EmailType::class, [
                "required" => true,
                "label" => "Adresse Email",
            ])
            ->add('age', IntegerType::class, [
                "required" => false,
                "label" => "Age",
                "empty_data" => null,
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Rôle',
                'choices' => [
                    'Correspondant' => 'ROLE_CORRESPONDANT',
                    'Rédacteur' => 'ROLE_REDACTEUR',
                    'Administrateur' => 'ROLE_ADMIN',
                ],
                'expanded' => false,
                'multiple' => false,
                'mapped' => true,
            ])
            ->get("roles")
            ->addModelTransformer(new CallbackTransformer(
                fn (?array $roles): ?string => $roles[0] ?? null,
                fn (?string $role): array => $role ? [$role] : []
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
