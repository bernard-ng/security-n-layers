<?php
declare(strict_types=1);

namespace App\Controller\Security;

use App\Entity\Security\PasswordResetToken;
use App\Entity\User;
use App\Event\Security\PasswordResetConfirmEvent;
use App\Event\Security\PasswordResetRequestEvent;
use App\Repository\UserRepository;
use App\Data\Security\PasswordResetConfirmData;
use App\Service\Security\InvalidTokenException;
use App\Service\Security\UserNotFoundException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Data\Security\PasswordResetRequestData;
use App\Form\Security\PasswordResetConfirmType;
use App\Form\Security\PasswordResetRequestType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class ResetPasswordController
 * @package App\Controller\Security
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class ResetPasswordController extends AbstractController
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
        if ($token->isExpiry() || $token->getUser() !== $user) {
            $this->addFlash('error', 'auth.account.password.expiredToken');
            return $this->redirectToRoute('app_auth_login');
        }

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
        } catch (InvalidTokenException $e) {
            $this->addFlash('error', 'auth.account.password.invalidToken');
            return $this->redirectToRoute('app_auth_login');
        }
    }
}
