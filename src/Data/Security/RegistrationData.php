<?php

declare(strict_types=1);

namespace App\Data\Security;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class RegistrationData
 * UniqueEntity("name", repositoryMethod="findBy", entityClass="App\Entity\User")
 * UniqueEntity("email", repositoryMethod="findBy", entityClass="App\Entity\User")
 * @package App\Data\Security
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class RegistrationData
{
    /**
     * @Assert\Regex("#[a-z0-9_]+#")
     * @Assert\NotBlank()
     */
    public string $name = '';

    /**
     * @Assert\Email()
     * @Assert\NotBlank()
     */
    public string $email = '';

    /**
     * @Assert\NotBlank(message="Please enter a password")
     * @Assert\Length(min="6", max="4096", minMessage="Your password should be at least {{ limit }} characters")
     */
    public string $plainPassword = '';

    /**
     * @Assert\IsTrue(message="You should agree to our terms.")
     */
    public bool $agreeTerms = false;
}
