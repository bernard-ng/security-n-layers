<?php

declare(strict_types=1);

namespace App\Event\Security;

use App\Entity\User;

/**
 * Class EmailVerificationEvent
 * @package App\Event\Security
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class EmailVerificationEvent
{

    /**
     * @var User
     */
    private User $user;
    /**
     * @var string
     */
    private string $email;

    /**
     * EmailVerificationEvent constructor.
     * @param User $user
     * @param string $email
     */
    public function __construct(User $user, string $email)
    {
        $this->user = $user;
        $this->email = $email;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
}
