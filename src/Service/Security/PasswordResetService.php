<?php

declare(strict_types=1);

namespace App\Service\Security;

use App\Data\Security\PasswordResetConfirmData;
use App\Data\Security\PasswordResetRequestData;
use App\Entity\Security\PasswordResetToken;
use App\Entity\User;
use App\Repository\Security\PasswordResetTokenRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

/**
 * Class PasswordResetService
 * @package App\Service\Security
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class PasswordResetService
{

    private UserRepository $repository;
    private TokenGeneratorInterface $tokenGenerator;
    private EntityManagerInterface $manager;
    private LoggerInterface $logger;
    private PasswordResetTokenRepository $tokenRepository;
    private UserPasswordEncoderInterface $passwordEncoder;

    /**
     * PasswordResetService constructor.
     * @param UserRepository $repository
     * @param PasswordResetTokenRepository $tokenRepository
     * @param TokenGeneratorInterface $tokenGenerator
     * @param EntityManagerInterface $manager
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param LoggerInterface $logger
     */
    public function __construct(
        UserRepository $repository,
        PasswordResetTokenRepository $tokenRepository,
        TokenGeneratorInterface $tokenGenerator,
        EntityManagerInterface $manager,
        UserPasswordEncoderInterface $passwordEncoder,
        LoggerInterface $logger
    ) {
        $this->repository = $repository;
        $this->tokenRepository = $tokenRepository;
        $this->tokenGenerator = $tokenGenerator;
        $this->manager = $manager;
        $this->logger = $logger;
        $this->passwordEncoder = $passwordEncoder;
    }


    /**
     * @param PasswordResetRequestData $data
     * @throws UserNotFoundException When the given email is not associated to user
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function reset(PasswordResetRequestData $data)
    {
        $user = $this->repository->findOneBy(['email', $data->email]);
        if ($user) {
            try {
                $token = (new PasswordResetToken())
                    ->setUser($user)
                    ->setToken($this->tokenGenerator->generateToken());
                $this->manager->persist($token);
                $this->manager->flush();

                // TODO: send an email with the confirmation token
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage(), $e->getTrace());
            }
        } else {
            throw new UserNotFoundException(
                sprintf("User with %s as Email could not be found.", $data->email)
            );
        }
    }

    /**
     * @param User $user
     * @param PasswordResetToken $token
     * @param PasswordResetConfirmData $data
     * @throws TokenNotFoundException
     * @throws TokenExpiredException
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function confirm(User $user, PasswordResetToken $token, PasswordResetConfirmData $data): void
    {
        $expectedToken = $this->tokenRepository->findOneBy(['user' => $user]);

        if ($expectedToken && $expectedToken === $token) {
            if ($token->isExpiried() || $token->getUser() !== $user) {
                $this->manager->remove($token);
                $this->manager->flush();
                throw  new TokenExpiredException(
                    sprintf("The token %s is expired", $token->getToken())
                );
            }

            $user->setPassword($this->passwordEncoder->encodePassword($user, $data->password));
            $this->manager->remove($token);
            $this->manager->persist($user);
            $this->manager->flush();
        } else {
            throw new TokenNotFoundException(
                sprintf("The token %s is not a valid one", $token->getToken())
            );
        }
    }
}
