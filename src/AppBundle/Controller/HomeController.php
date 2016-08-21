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
    public function homeAction(Request $request) {
        $tasks = $this->getDoctrine()
                        ->getRepository('AppBundle:Task')
                        ->findAll();

        $csrf_token = $this->getNewCSRFToken();

        return $this->render(
            'home.html.twig',
            array(
                'tasks' => $tasks,
                'csrf_token' => $csrf_token)
        );
    }

    private function createDeleteForm($taskId) {
        return $this->createFormBuilder(array('id' => $taskId))
            ->add('id', TextType::class,
                array(
                    'attr' => array('hidden' => true)))
            ->setAction($this->generateUrl('delete_task', array('id' => $taskId)))
            ->setMethod('POST')
            ->getForm();
    }


    private function getNewCSRFToken() {
        $csrfProvider = $this->get('security.csrf.token_manager');

        return $csrfProvider->refreshToken('delete_task');

    }
}
