<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Event\Security\{
    EmailVerificationEvent,
    PasswordResetConfirmEvent,
    PasswordResetRequestEvent,
    RegistrationRequestEvent,
    AuthenticationSuccessEvent
};
use App\Service\Security\{
    AuthenticationService,
    TokenNotFoundException,
    LoginService,
    PasswordResetService,
    TokenExpiredException,
    TooManyEmailChangeException,
    UserNotFoundException
};
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class AuthenticationEventSubscriber
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class SecurityEventSubscriber implements EventSubscriberInterface
{

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            AuthenticationSuccessEvent::class => 'onAuthenticationSuccess',
            RegistrationRequestEvent::class => 'onRegistrationRequest',
            PasswordResetRequestEvent::class => 'onPasswordResetRequest',
            PasswordResetConfirmEvent::class => 'onPasswordResetConfirm',
            EmailVerificationEvent::class => 'onEmailVerification',
        ];
    }

    /**
     * @param RegistrationRequestEvent $event
     * @param AuthenticationService $service
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function onRegistrationRequest(RegistrationRequestEvent $event, AuthenticationService $service): void
    {
        $service->register($event->getData());
    }

    /**
     * @param AuthenticationSuccessEvent $event
     * @param LoginService $service
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event, LoginService $service): void
    {
        $service->register($event->getUser(), $event->getRequest());
    }

    /**
     * @param EmailVerificationEvent $event
     * @param AuthenticationService $service
     * @throws TooManyEmailChangeException
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function onEmailVerification(EmailVerificationEvent $event, AuthenticationService $service)
    {
        $service->verification($event->getUser(), $event->getEmail());
    }

    /**
     * @param PasswordResetConfirmEvent $event
     * @param PasswordResetService $service
     * @throws TokenExpiredException
     * @throws TokenNotFoundException
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function onPasswordResetConfirm(PasswordResetConfirmEvent $event, PasswordResetService $service): void
    {
        $service->confirm($event->getUser(), $event->getToken(), $event->getData());
    }

    /**
     * @param PasswordResetRequestEvent $event
     * @param PasswordResetService $service
     * @throws UserNotFoundException
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function onPasswordResetRequest(PasswordResetRequestEvent $event, PasswordResetService $service): void
    {
        $service->reset($event->getData());
    }
}
