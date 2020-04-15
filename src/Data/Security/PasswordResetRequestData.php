<?php
namespace App\Data\Security;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class PasswordResetRequestData
 * @package App\Data\Security
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class PasswordResetRequestData
{

    /**
     * @Assert\Email()
     * @Assert\NotBlank()
     */
    public string $email;
}
