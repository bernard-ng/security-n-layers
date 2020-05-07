<?php
declare(strict_types=1);

namespace App\Service\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

/**
 * Class AuthenticationService
 * @package App\Service\Security
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class AuthenticationService
{

    private const PASSWORD_RESET_ROUTE = 'app_auth_password_reset';

    private EntityManagerInterface $manager;
    private EventDispatcherInterface $eventDispatcher;
    private TokenGeneratorInterface $tokenGenerator;
    private UserPasswordEncoderInterface $passwordEncoder;
    private LoggerInterface $logger;
    private UserRepository $repository;
    private UrlGeneratorInterface $urlGenerator;

    /**
     * AuthenticationService constructor.
     * @param EntityManagerInterface $manager
     * @param EventDispatcherInterface $eventDispatcher
     * @param TokenGeneratorInterface $tokenGenerator
     * @param UrlGeneratorInterface $urlGenerator
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param UserRepository $repository
     * @param LoggerInterface $logger
     */
    public function __construct(
        EntityManagerInterface $manager,
        EventDispatcherInterface $eventDispatcher,
        TokenGeneratorInterface $tokenGenerator,
        UrlGeneratorInterface $urlGenerator,
        UserPasswordEncoderInterface $passwordEncoder,
        UserRepository $repository,
        LoggerInterface $logger
    )
    {
        $this->manager = $manager;
        $this->eventDispatcher = $eventDispatcher;
        $this->tokenGenerator = $tokenGenerator;
        $this->passwordEncoder = $passwordEncoder;
        $this->logger = $logger;
        $this->repository = $repository;
        $this->urlGenerator = $urlGenerator;
    }


    /**
     * @param User $user
     * @param $data
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function register(User $user, $data): void
    {
        // encode the plain password
        $user->setPassword(
            $this->passwordEncoder->encodePassword(
                $user,
                $data['plainPassword']
            )
        );
        $user->setAccountConfirmationToken($this->tokenGenerator->generateToken());
        $this->manager->persist($user);
        $this->manager->flush();
        // TODO: send email
    }
}
