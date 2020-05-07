<?php

declare(strict_types=1);

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
    private $data;

    /**
     * AccountRegisteredEvent constructor.
     * @param User $user
     * @param mixed $data
     */
    public function __construct(User $user, $data)
    {
        $this->user = $user;
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
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
}
