<?php
declare(strict_types=1);

namespace App\Event\Security;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class AuthenticationSuccessEvent
 * @package App\Event\Security
 * @author bernard-ng <ngandubernard@gmail.com>
 */
final class AuthenticationSuccessEvent
{
    private Request $request;
    private User $user;

    /**
     * AuthenticationSuccessEvent constructor.
     * @param Request $request
     * @param User|UserInterface $user
     */
    public function __construct(Request $request, User $user)
    {
        $this->request = $request;
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }
}
