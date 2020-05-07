<?php
declare(strict_types=1);

namespace App\EventSubscriber;

use App\Event\Security\PasswordResetConfirmEvent;
use App\Event\Security\PasswordResetRequestEvent;
use App\Service\Security\AuthenticationService;
use App\Event\Security\AccountRegisteredEvent;
use App\Event\Security\AuthenticationSuccessEvent;
use App\Service\Security\InvalidTokenException;
use App\Service\Security\LoginService;
use App\Service\Security\PasswordResetService;
use App\Service\Security\UserNotFoundException;
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
            AccountRegisteredEvent::class => 'onAccountRegistered',
            PasswordResetRequestEvent::class => 'onPasswordResetRequest',
            PasswordResetConfirmEvent::class => 'onPasswordResetConfirm'
        ];
    }

    /**
     * @param AccountRegisteredEvent $event
     * @param AuthenticationService $service
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function onAccountRegistered(AccountRegisteredEvent $event, AuthenticationService $service): void
    {
        $service->register($event->getUser(), $event->getData());
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
     * @param PasswordResetConfirmEvent $event
     * @param PasswordResetService $service
     * @throws InvalidTokenException
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
