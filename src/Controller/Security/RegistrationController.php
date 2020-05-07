<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Event\Security\AccountRegisteredEvent;
use App\Form\Security\RegistrationType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class RegistrationController
 * @package App\Controller
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class RegistrationController extends AbstractController
{

    /**
     * @Route("/auth/register", name="app_auth_register", methods={"GET", "POST"})
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param EventDispatcherInterface $eventDispatcher
     * @return Response
     */
    public function __invoke(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        EventDispatcherInterface $eventDispatcher
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $eventDispatcher->dispatch(new AccountRegisteredEvent($user, $form->getData()));
            return $this->redirectToRoute('app_auth_login');
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/auth/register/confirm/:token", name="app_auth_register_confirm", methods={"GET"})
     * @param Request $request
     * @param string $token
     * @param UserRepository $repository
     * @return Response
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function confirm(Request $request, string $token, UserRepository $repository): Response
    {
        /** @var User $user */
        $user = $repository->findOneBy(['account_confirmation_token', $token]);

        if ($user) {
            $user->setAccountConfirmed();
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'auth.account.confirmation.success');
            return $this->redirectToRoute('app_auth_login');
        }

        $this->addFlash('error', 'auth.account.confirmation.invalidToken');
        return $this->redirectToRoute('app_auth_login');
    }
}
