<?php

declare(strict_types=1);

namespace App\Event\Security;

use App\Entity\Security\PasswordResetToken;
use App\Entity\User;

/**
 * Class PasswordResetTokenCreatedEvent
 * @package App\Event\Security
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class PasswordResetTokenCreatedEvent
{

    private User $user;
    private PasswordResetToken $token;

    /**
     * PasswordResetTokenCreatedEvent constructor.
     * @param User $user
     * @param PasswordResetToken $token
     */
    public function __construct(User $user, PasswordResetToken $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return PasswordResetToken
     */
    public function getToken(): PasswordResetToken
    {
        return $this->token;
    }
}
