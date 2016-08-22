<?php

namespace AppBundle\Controller;

use AppBundle\Form\TaskType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    /**
     * @Route("/", name="home")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function homeAction() {
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
