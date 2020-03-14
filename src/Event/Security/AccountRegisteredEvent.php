<?php
namespace App\Event\Security;

use App\Entity\User;

/**
 * Class AccountRegisteredEvent
 * @package App\Event\Security
 * @author bernard-ng <ngandubernard@gmail.com>
 */
final class AccountRegisteredEvent
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
}
