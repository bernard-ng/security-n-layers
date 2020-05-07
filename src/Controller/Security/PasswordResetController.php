<?php

declare(strict_types=1);

namespace App\Controller\Security;

use App\Entity\User;
use App\Entity\Security\PasswordResetToken;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Security\{TokenExpiredException, UserNotFoundException, TokenNotFoundException};
use App\Data\Security\{PasswordResetConfirmData, PasswordResetRequestData};
use App\Form\Security\{PasswordResetConfirmType, PasswordResetRequestType};
use App\Event\Security\{PasswordResetRequestEvent, PasswordResetConfirmEvent};

/**
 * Class ResetPasswordController
 * @package App\Controller\Security
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class PasswordResetController extends AbstractController
{

    private EventDispatcherInterface $eventDispatcher;

    /**
     * ResetPasswordController constructor.
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @Route("/auth/password/reset", name="app_auth_password_reset", methods={"GET", "POST"})
     * @param Request $request
     * @return Response
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function request(Request $request): Response
    {
        $data = new PasswordResetRequestData();
        $form = $this->createForm(PasswordResetRequestType::class, $data);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->eventDispatcher->dispatch(new PasswordResetRequestEvent($data));
                $this->addFlash('success', 'auth.account.password.resetRequestSuccess');
            } catch (UserNotFoundException $e) {
                $this->addFlash('error', 'auth.account.password.invalidEmail');
            } finally {
                return $this->redirectToRoute('app_auth_login');
            }
        }

        return $this->render('security/password_reset.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/auth/password/reset/{id}/{token}", name="app_auth_password_reset_confirm", methods={"GET"})
     * @param Request $request
     * @param User $user
     * @param PasswordResetToken $token
     * @return Response
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function reset(Request $request, User $user, PasswordResetToken $token): Response
    {
        try {
            $data = new PasswordResetConfirmData();
            $form = $this->createForm(PasswordResetConfirmType::class, $data);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->eventDispatcher->dispatch(new PasswordResetConfirmEvent($user, $token, $data));
                $this->addFlash('success', 'auth.account.password.resetProcessSuccess');
                return $this->redirectToRoute('app_auth_login');
            }

            return $this->render('security/password_reset_confirm.html.twig', [
                'form' => $form->createView()
            ]);
        } catch (TokenNotFoundException $e) {
            $this->addFlash('error', 'auth.account.password.invalidToken');
        } catch (TokenExpiredException $e) {
            $this->addFlash('error', 'auth.account.password.expiredToken');
        } finally {
            return $this->redirectToRoute('app_auth_login');
        }
    }
}
