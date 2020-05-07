<?php

declare(strict_types=1);

namespace App\Controller\Security;

use App\Data\Security\RegistrationData;
use App\Entity\Security\EmailVerification;
use App\Entity\User;
use App\Event\Security\EmailVerificationConfirmEvent;
use App\Event\Security\RegistrationRequestEvent;
use App\Form\Security\RegistrationForm;
use App\Service\Security\TokenExpiredException;
use App\Service\Security\TokenNotFoundException;
use App\Service\Security\TooManyEmailChangeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RegistrationController
 * @package App\Controller
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class RegistrationController extends AbstractController
{

    private EventDispatcherInterface $eventDispatcher;

    /**
     * RegistrationController constructor.
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @Route("/auth/register", name="app_auth_register", methods={"GET", "POST"})
     * @param Request $request
     * @return Response
     */
    public function register(Request $request): Response
    {
        $data = new RegistrationData();
        $form = $this->createForm(RegistrationForm::class, $data);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->eventDispatcher->dispatch(new RegistrationRequestEvent($data));
            return $this->redirectToRoute('app_auth_login');
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/auth/email-confirm/{id}/{token}", name="app_auth_email_confirm", methods={"GET"})
     * @param User $user
     * @param EmailVerification $verification
     * @return Response
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function confirm(User $user, EmailVerification $verification): Response
    {
        try {
            $this->eventDispatcher->dispatch(new EmailVerificationConfirmEvent($user, $verification));
            $this->addFlash('success', 'auth.account.confirmation.success');
            return $this->redirectToRoute('app_auth_login');
        } catch (TokenNotFoundException $e) {
            $this->addFlash('error', 'auth.account.confirmation.invalidToken');
        } catch (TokenExpiredException $e) {
            $this->addFlash('error', 'auth.account.confirmation.expiredToken');
        } catch (TooManyEmailChangeException $e) {
            $this->addFlash('error', 'auth.account.confirmation.tooManyEmailChange');
        } finally {
            return $this->redirectToRoute('app_auth_register');
        }
    }
}
