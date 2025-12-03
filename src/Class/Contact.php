<?php
namespace App\Class;

use Symfony\Component\Validator\Constraints as Assert;

class Contact
{
    #[Assert\NotBlank(message: 'Le prénom est obligatoire')]
    private string $firstName;
    #[Assert\NotBlank(message: 'Le nom est obligatoire')]
    private string $lastName;
    #[Assert\NotBlank(message: 'L\'email est obligatoire')]
    #[Assert\Email(message: 'L\'email n\'est pas valide')]
    private string $email;
    #[Assert\NotBlank(message: 'Le sujet est obligatoire')]
    #[Assert\Length(
        min: 5,
        max: 255,
        minMessage: 'Le sujet doit contenir au moins {{ limit }} caractères',
        maxMessage: 'Le sujet ne peut pas dépasser {{ limit }} caractères',
    )]
    private string $subject;
    #[Assert\NotBlank(message: 'Le message est obligatoire')]
    #[Assert\Length(
        min: 15,
        minMessage: 'Le message doit contenir au moins {{ limit }} caractères',
    )]
    private string $message;

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): Contact
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): Contact
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): Contact
    {
        $this->email = $email;
        return $this;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): Contact
    {
        $this->subject = $subject;
        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): Contact
    {
        $this->message = $message;
        return $this;
    }
}

