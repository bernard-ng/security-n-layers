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

    private LoginService $loginService;
    private PasswordResetService $passwordResetService;
    private AuthenticationService $authenticationService;

    /**
     * SecurityEventSubscriber constructor.
     * @param LoginService $loginService
     * @param PasswordResetService $passwordResetService
     * @param AuthenticationService $authenticationService
     */
    public function __construct(
        LoginService $loginService,
        PasswordResetService $passwordResetService,
        AuthenticationService $authenticationService
    ) {
        $this->loginService = $loginService;
        $this->passwordResetService = $passwordResetService;
        $this->authenticationService = $authenticationService;
    }

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
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function onRegistrationRequest(RegistrationRequestEvent $event): void
    {
        $this->authenticationService->register($event->getData());
    }

    /**
     * @param AuthenticationSuccessEvent $event
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event): void
    {
        $this->loginService->register($event->getUser(), $event->getRequest());
    }

    /**
     * @param EmailVerificationEvent $event
     * @throws TooManyEmailChangeException
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function onEmailVerification(EmailVerificationEvent $event)
    {
        $this->authenticationService->verification($event->getUser(), $event->getEmail());
    }

    /**
     * @param PasswordResetConfirmEvent $event
     * @throws TokenExpiredException
     * @throws TokenNotFoundException
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function onPasswordResetConfirm(PasswordResetConfirmEvent $event): void
    {
        $this->passwordResetService->confirm($event->getUser(), $event->getToken(), $event->getData());
    }

    /**
     * @param PasswordResetRequestEvent $event
     * @throws UserNotFoundException
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function onPasswordResetRequest(PasswordResetRequestEvent $event): void
    {
        $this->passwordResetService->reset($event->getData());
    }
}
