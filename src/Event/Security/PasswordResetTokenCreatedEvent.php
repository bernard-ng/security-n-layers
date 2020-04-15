<?php

namespace App\Event\Security;

use App\Entity\User;

/**
 * Class PasswordResetTokenCreatedEvent
 * @package App\Event\Security
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class PasswordResetTokenCreatedEvent
{

    private User $user;
    private string $token;

    /**
     * PasswordResetTokenCreatedEvent constructor.
     * @param User $user
     * @param string $token
     */
    public function __construct(User $user, string $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
}
