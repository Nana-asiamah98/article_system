<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserRegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email',null,[
                'required' => false,

            ])
            ->add('plainPassword',PasswordType::class,[
                'label' => 'Password',
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Choose a password'
                    ]),
                    new Length([
                        'min' => 5,
                        'minMessage' => 'Your password is below 5 characters'
                    ])
                ]
            ])
      ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
