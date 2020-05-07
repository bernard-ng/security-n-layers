<?php
declare(strict_types=1);

namespace App\Event\Security;

use App\Data\Security\PasswordResetConfirmData;
use App\Entity\Security\PasswordResetToken;
use App\Entity\User;

/**
 * Class PasswordResetConfirmEvent
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class PasswordResetConfirmEvent
{

    private User $user;
    private PasswordResetToken $token;
    private PasswordResetConfirmData $data;

    /**
     * PasswordResetConfirmEvent constructor.
     * @param User $user
     * @param PasswordResetToken $token
     * @param PasswordResetConfirmData $data
     */
    public function __construct(User $user, PasswordResetToken $token, PasswordResetConfirmData $data)
    {
        $this->user = $user;
        $this->token = $token;
        $this->data = $data;
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

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
}
