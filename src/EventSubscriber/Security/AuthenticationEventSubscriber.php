<?php

namespace App\EventSubscriber\Security;

use App\Entity\Security\Login;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use App\Event\Security\AccountRegisteredEvent;
use App\Event\Security\AuthenticationSuccessEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

/**
 * Class AuthenticationEventSubscriber
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class AuthenticationEventSubscriber implements EventSubscriberInterface
{

    private MailerInterface $mailer;
    private EntityManagerInterface $manager;

    /**
     * AuthenticationEventSubscriber constructor.
     * @param MailerInterface $mailer
     * @param EntityManagerInterface $manager
     */
    public function __construct(MailerInterface $mailer, EntityManagerInterface $manager)
    {
        $this->mailer = $mailer;
        $this->manager = $manager;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            AuthenticationSuccessEvent::class => 'onAuthenticationSuccess',
            AccountRegisteredEvent::class => 'onAccountRegistered'
        ];
    }

    /**
     * @param AccountRegisteredEvent $event
     * @param TokenGeneratorInterface $tokenGenerator
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function onAccountRegistered(AccountRegisteredEvent $event, TokenGeneratorInterface $tokenGenerator): void
    {
        $user = $event->getUser();
        $user->setAccountConfirmationToken($tokenGenerator->generateToken());
        $this->manager->persist($user);
        $this->manager->flush();
        // TODO: send email
    }


    /**
     * @param AuthenticationSuccessEvent $event
     * @throws \Exception
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event): void
    {
        $request = $event->getRequest();
        $login = (new Login())
            ->setIp($request->getClientIp())
            ->setLocation("-")
            ->setDevice("-")
            ->setUser($event->getUser());

        $this->manager->persist($login);
        $this->manager->flush();
    }
}
