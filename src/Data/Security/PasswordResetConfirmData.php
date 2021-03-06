<?php

declare(strict_types=1);

namespace App\Data\Security;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class PasswordResetData
 * @package App\Data\Security
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class PasswordResetConfirmData
{

    /**
     * @Assert\Length(min="6")
     * @Assert\NotBlank()
     */
    public string $password;
}
