<?php

namespace AppBundle\Controller;

use AppBundle\Form\TaskType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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

        $task_forms = array_map(
            function($task) {
                return $this->createDeleteForm($task->getId())->createView();
            }, $tasks
        );

        return $this->render(
            'home.html.twig',
            array(
                'tasks' => $tasks,
                'task_forms' => $task_forms)
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
}
