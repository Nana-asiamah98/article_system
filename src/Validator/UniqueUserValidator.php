<?php

namespace App\Validator;

use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueUserValidator extends ConstraintValidator
{
    /**
     * @var UserRepository
     */
    private $userRepository;


    /**
     * UniqueUserValidator constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function validate($value, Constraint $constraint)
    {

        /** @var $user */
        $user = $this->userRepository->findOneBy(['email'=>$value]);

        if(!$user){
            return;
        }

        /* @var $constraint App\Validator\UniqueUser */

        $this->context->buildViolation($constraint->message)
            ->addViolation();
    }
}
