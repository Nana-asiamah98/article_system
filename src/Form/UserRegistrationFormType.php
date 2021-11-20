<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserRegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email',EmailType::class)
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
            ->add('agreedTerms',CheckboxType::class,[
                'mapped' => false,
                'constraints' => [
                    new  IsTrue([
                        'message' => 'Kindly select button'
                    ])
                ]
            ]);
      ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
