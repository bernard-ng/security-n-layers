<?php

declare(strict_types=1);

namespace App\Event\Security;

use App\Entity\Security\EmailVerification;
use App\Entity\User;

/**
 * Class EmailVerificationCreatedEvent
 * @package App\Event\Security
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class EmailVerificationCreatedEvent
{

    private User $user;
    private EmailVerification $verification;

    /**
     * EmailVerificationCreatedEvent constructor.
     * @param User $user
     * @param EmailVerification $verification
     */
    public function __construct(User $user, EmailVerification $verification)
    {
        $this->user = $user;
        $this->verification = $verification;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return EmailVerification
     */
    public function getVerification(): EmailVerification
    {
        return $this->verification;
    }
}
