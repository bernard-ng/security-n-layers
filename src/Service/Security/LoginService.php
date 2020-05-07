<?php

declare(strict_types=1);

namespace App\Service\Security;

use App\Entity\Security\Login;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class LoginService
 * @package App\Service\Security
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class LoginService
{

    private EntityManagerInterface $manager;
    private LoggerInterface $logger;

    /**
     * LoginService constructor.
     * @param EntityManagerInterface $manager
     * @param LoggerInterface $logger
     */
    public function __construct(EntityManagerInterface $manager, LoggerInterface $logger)
    {
        $this->manager = $manager;
        $this->logger = $logger;
    }

    /**
     * @param User $user
     * @param Request $request
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function register(User $user, Request $request): void
    {
        try {
            $login = (new Login())
                ->setIp($request->getClientIp())
                ->setLocation("-")
                ->setDevice("-")
                ->setUser($user);

            // TODO: Save Location and Device for each Login
            $this->manager->persist($login);
            $this->manager->flush();
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
        }
    }
}
