<?php
declare(strict_types=1);

namespace App\Controller\Security;

use App\Entity\User;
use App\Event\Security\PasswordResetTokenCreatedEvent;
use App\Repository\UserRepository;
use App\Data\Security\PasswordResetData;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Data\Security\PasswordResetRequestData;
use App\Form\Security\PasswordResetConfirmType;
use App\Form\Security\PasswordResetRequestType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

/**
 * Class ResetPasswordController
 * @package App\Controller\Security
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class ResetPasswordController extends AbstractController
{

    /**
     * @param Request $request
     * @param UserRepository $repository
     * @param TokenGeneratorInterface $generator
     * @param EventDispatcherInterface $eventDispatcher
     * @return Response
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function request(
        Request $request,
        UserRepository $repository,
        TokenGeneratorInterface $generator,
        EventDispatcherInterface $eventDispatcher
    ): Response {
        $data = new PasswordResetRequestData();
        $form = $this->createForm(PasswordResetRequestType::class, $data);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $repository->findOneBy(['email', $data->email]);

            if ($user) {
                $token = $generator->generateToken();
                $user->setPasswordResetToken($token);
                $this->getDoctrine()->getManager()->flush();

                $eventDispatcher->dispatch(new PasswordResetTokenCreatedEvent($user, $token));
                $this->addFlash('success', 'auth.account.password.resetRequestSuccess');
                return $this->redirectToRoute('app_auth_login');
            }

            $this->addFlash('error', 'auth.account.password.invalidEmail');
            return $this->redirectToRoute('app_auth_login');
        }

        return $this->render('security/password_reset_request.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/auth/password/reset/:token", name="app_auth_password_reset", methods={"GET"})
     * @param Request $request
     * @param string $token
     * @param UserRepository $repository
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function reset(
        Request $request,
        string $token,
        UserRepository $repository,
        UserPasswordEncoderInterface $encoder
    ): Response {
        /** @var User $user */
        $user = $repository->findOneBy(['password_reset_token', $token]);

        if ($user) {
            $data = new PasswordResetData();
            $form = $this->createForm(PasswordResetConfirmType::class, $data);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $user->resetPassword($encoder->encodePassword($user, $data->password));
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('success', 'auth.account.password.resetProcessSuccess');
                return $this->redirectToRoute('app_auth_login');
            }

            return $this->render('security/password_reset.html.twig', [
                'form' => $form->createView()
            ]);
        }

        $this->addFlash('error', 'auth.account.password.invalidToken');
        return $this->redirectToRoute('app_auth_login');
    }
}
