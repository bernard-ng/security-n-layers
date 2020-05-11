<?php

declare(strict_types=1);

namespace App\Notification;

use App\Event\Security\EmailVerificationCreatedEvent;
use App\Event\Security\PasswordResetTokenCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

/**
 * Class SecurityNotification
 * @package App\Notification
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class SecurityNotification implements EventSubscriberInterface
{

    private MailerInterface $mailer;
    private EmailFactory $factory;

    /**
     * SecurityNotification constructor.
     * @param EmailFactory $factory
     * @param MailerInterface $mailer
     */
    public function __construct(EmailFactory $factory, MailerInterface $mailer)
    {
        $this->mailer = $mailer;
        $this->factory = $factory;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            EmailVerificationCreatedEvent::class => 'onEmailVerificationCreated',
            PasswordResetTokenCreatedEvent::class => 'onPasswordResetTokenCreated'
        ];
    }

    /**
     * @param EmailVerificationCreatedEvent $event
     * @throws TransportExceptionInterface
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function onEmailVerificationCreated(EmailVerificationCreatedEvent $event)
    {
        $user = $event->getUser();
        $email = $this->factory->makeFromTemplate('mails/security/email_confirmation.html.twig', [
            'token' => $event->getVerification()->getToken(),
            'id' => $user->getId()
        ])
            ->to(new Address($event->getVerification()->getEmail(), $user->getName()))
            ->priority(Email::PRIORITY_HIGH)
            ->subject("SouvenirCloud - Confirmation de votre adresse mail");
        $this->mailer->send($email);
    }

    /**
     * @param PasswordResetTokenCreatedEvent $event
     * @throws TransportExceptionInterface
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function onPasswordResetTokenCreated(PasswordResetTokenCreatedEvent $event)
    {
        $user = $event->getUser();
        $email = $this->factory->makeFromTemplate('mails/security/password_reset_token.html.twig', [
            'token' => $event->getToken()->getToken(),
            'id' => $user->getId()
        ])
            ->to(new Address($user->getEmail(), $user->getName()))
            ->priority(Email::PRIORITY_HIGH)
            ->subject("SouvenirCloud - Instruction de rappel de mot de passe");
        $this->mailer->send($email);
    }
}
