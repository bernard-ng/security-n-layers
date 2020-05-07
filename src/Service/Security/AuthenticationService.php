<?php

declare(strict_types=1);

namespace App\Service\Security;

use App\Data\Security\RegistrationData;
use App\Entity\Security\EmailVerification;
use App\Entity\User;
use App\Event\Security\EmailVerificationEvent;
use App\Repository\Security\EmailVerificationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

/**
 * Class AuthenticationService
 * @package App\Service\Security
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class AuthenticationService
{
    private EntityManagerInterface $manager;
    private EventDispatcherInterface $eventDispatcher;
    private TokenGeneratorInterface $tokenGenerator;
    private UserPasswordEncoderInterface $passwordEncoder;
    private LoggerInterface $logger;
    private UserRepository $repository;
    private EmailVerificationRepository $verificationRepository;

    /**
     * AuthenticationService constructor.
     * @param EntityManagerInterface $manager
     * @param EventDispatcherInterface $eventDispatcher
     * @param TokenGeneratorInterface $tokenGenerator
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param UserRepository $repository
     * @param EmailVerificationRepository $verificationRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        EntityManagerInterface $manager,
        EventDispatcherInterface $eventDispatcher,
        TokenGeneratorInterface $tokenGenerator,
        UserPasswordEncoderInterface $passwordEncoder,
        UserRepository $repository,
        EmailVerificationRepository $verificationRepository,
        LoggerInterface $logger
    ) {
        $this->manager = $manager;
        $this->eventDispatcher = $eventDispatcher;
        $this->tokenGenerator = $tokenGenerator;
        $this->passwordEncoder = $passwordEncoder;
        $this->logger = $logger;
        $this->repository = $repository;
        $this->verificationRepository = $verificationRepository;
    }


    /**
     * @param RegistrationData $data
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function register(RegistrationData $data): void
    {
        $user = new User();
        $user->setName($data->name);
        $user->setEmail($data->email);
        $user->setPassword($this->passwordEncoder->encodePassword($user, $data->plainPassword));

        $this->manager->persist($user);
        $this->manager->flush();

        $this->eventDispatcher->dispatch(new EmailVerificationEvent($user, $data->email));
    }

    /**
     * @param User $user
     * @param string $email
     * @throws TooManyEmailChangeException
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function verification(User $user, string $email): void
    {
        $lastRequest = $this->verificationRepository->findLastRequestForUser($user);
        if ($lastRequest && $lastRequest->getCreatedAt() > new \DateTime('-1 hour')) {
            throw new TooManyEmailChangeException(
                sprintf("Cannot change email multiple times in less then an hour")
            );
        } else {
            if ($lastRequest) {
                $this->manager->remove($lastRequest);
            }
        }

        $emailVerification = (new EmailVerification())
            ->setEmail($email)
            ->setUser($user)
            ->setToken($this->tokenGenerator->generateToken());
        $this->manager->persist($emailVerification);
        $this->manager->flush();

        // TODO: send email with the verification token
    }

    /**
     * @param EmailVerification $verification
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function confirm(EmailVerification $verification): void
    {
        $verification->getUser()->setEmail($verification->getEmail());
        $this->manager->flush();
    }
}
