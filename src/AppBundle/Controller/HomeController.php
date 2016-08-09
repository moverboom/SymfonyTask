<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    /**
     * @Route("/", name="home")
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
