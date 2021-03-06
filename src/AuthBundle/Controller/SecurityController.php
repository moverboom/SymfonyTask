<?php

namespace AuthBundle\Controller;

use AuthBundle\Entity\User;
use AuthBundle\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use AuthBundle\Controller\DenyAuthenticatedController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class SecurityController extends Controller implements DenyAuthenticatedController
{
    /**
     * @Route("/login", name="login")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction(Request $request) {
        $authUtils = $this->get('security.authentication_utils');

        $lastError = $authUtils->getLastAuthenticationError();

        $lastUsername = $authUtils->getLastUsername();
        return $this->render('AuthBundle::login.html.twig',
            array(
                'last_username' => $lastUsername,
                'error' => $lastError
            ));
    }

    /**
     * @Route("/register", name="register")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request) {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $password = $this->get('security.password_encoder')->encodePassword($user, $user->getPlainPassword());

            $user->setPassword($password);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->sendRegisterMail($user);

            return $this->redirectToRoute('home');
        }

        return $this->render(
            'AuthBundle::register.html.twig',
            array(
                'form' => $form->createView()
            )
        );
    }

    private function sendRegisterMail(User $user) {
        $message = \Swift_Message::newInstance()
            ->setSubject('Welcome ' . $user->getUsername())
            ->setFrom('mail@taskmanager.com')
            ->setTo($user->getEmail())
            ->setBody(
                $this->renderView(
                    'AuthBundle:templates/mails:mail_registered.html.twig',
                    array('name' => $user->getUsername())
                ), 'text/html'
            );
        $this->get('mailer')->send($message);
    }
}
