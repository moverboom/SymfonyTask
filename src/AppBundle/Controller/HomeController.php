<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class HomeController extends Controller
{
    /**
     * @Route("/", name="home")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function homeAction() {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Please login');


        $tasks = $this->getDoctrine()
                        ->getRepository('AppBundle:Task')
                        ->findAll();

        $csrf_token = $this->generateCSRFToken();

        return $this->render(
            'home.html.twig',
            array(
                'tasks' => $tasks,
                'csrf_token' => $csrf_token)
        );
    }

    /**
     * Generate a new CRSF token for the current user
     *
     * @return \Symfony\Component\Security\Csrf\CsrfToken
     */
    private function generateCSRFToken() {
        $csrfProvider = $this->get('security.csrf.token_manager');

        return $csrfProvider->refreshToken($this->get('security.token_storage')->getToken());

    }

    /**
     * @Route("/token", name="token")
     *
     * @return Response
     */
    public function getUserSecurityToken() {
        return new Response($this->get('security.token_storage')->getToken());
    }
}
