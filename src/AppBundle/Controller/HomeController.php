<?php

namespace AppBundle\Controller;

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
        $tasks = $this->getDoctrine()
                        ->getRepository('AppBundle:Task')
                        ->findAll();
        return $this->render(
            'home.html.twig',
            array('tasks' => $tasks)
        );
    }
}
