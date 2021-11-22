<?php


namespace App\Entity;


use App\Validator\UniqueUser;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


class UserRegistrationFormModel
{

    /**
     * @Assert\Email
     * @Assert\NotBlank(message="Please enter an email")
     * @UniqueUser()
     * */
    public $email;

    /**
     * @Assert\NotBlank (message="Enter Password")
     * @Assert\Length(min="5",minMessage="Password shouldn't be less than 5")
     */
    public $plainPassword;

    /**
     * @Assert\IsTrue (message="Kindly select button")
     */
    public $agreedTerms;
}
