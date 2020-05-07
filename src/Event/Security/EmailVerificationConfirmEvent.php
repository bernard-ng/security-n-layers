<?php

declare(strict_types=1);

namespace App\Event\Security;

use App\Entity\Security\EmailVerification;
use App\Entity\User;

/**
 * Class EmailVerificationConfirmEvent
 * @package App\Event\Security
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class EmailVerificationConfirmEvent
{

    private EmailVerification $verification;

    /**
     * EmailVerificationConfirmEvent constructor.
     * @param User $user
     * @param EmailVerification $verification
     */
    public function __construct(User $user, EmailVerification $verification)
    {
        $this->verification = $verification;
    }

    /**
     * @return EmailVerification
     */
    public function getVerification(): EmailVerification
    {
        return $this->verification;
    }
}
