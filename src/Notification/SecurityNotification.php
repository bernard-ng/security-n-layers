<?php

declare(strict_types=1);

namespace App\Notification;

use App\Event\Security\EmailVerificationCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
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
            EmailVerificationCreatedEvent::class => 'onEmailVerificationCreated'
        ];
    }

    /**
     * @param EmailVerificationCreatedEvent $event
     * @throws TransportExceptionInterface
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function onEmailVerificationCreated(EmailVerificationCreatedEvent $event)
    {
        $email = $this->factory->makeFromTemplate('mails/security/email_confirmation.html.twig', [
            'token' => $event->getVerification()->getToken(),
            'id' => $event->getUser()->getId()
        ])
            ->to($event->getVerification()->getEmail())
            ->priority(Email::PRIORITY_HIGH)
            ->subject("Confirmation de votre adresse mail");
        $this->mailer->send($email);
    }
}
