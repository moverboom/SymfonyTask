<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class HomeController extends Controller
{
    /**
     * @Route("/", name="index")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction() {

        if($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('home');
        }

        return $this->render('index.html.twig');
    }
}
