<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Task;
use AppBundle\Form\TaskType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Security("has_role('ROLE_USER')")
 * @Route("/task")
 *
 * Class TaskController
 * @package AppBundle\Controller
 */
class TaskController extends Controller
{

    /**
     * @Route("", name="home")
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
     * @Route("/add", name="add_task")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addTaskAction(Request $request) {
        $task = new Task();

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();
            
            return $this->redirectToRoute('home');
        }

        return $this->render('tasks/add.html.twig', array(
            'form' => $form->createView(),
            'task' => $task
        ));
    }

    /**
     * @Route("/edit/{id}", name="edit_task")
     *
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editTaskAction($id, Request $request) {
        $task = $this->findTaskById($id);

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('tasks/add.html.twig', array(
            'form' => $form->createView(),
            'task' => $task
        ));
    }

    /**
     * @Route("/reschedule", name="reschedule_task")
     * @Method({"POST"})
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function rescheduleTaskAction(Request $request) {
        $requestData = $request->request->get('task');

        if ($this->isCsrfTokenValid($this->getUserToken(), $requestData['_token'])) {
            $task = $this->findTaskById($requestData['id']);

            $task->setCompleted(false);
            $task->setDeadline(\DateTime::createFromFormat('Y-m-d H:i', $requestData['deadline']));

            $em = $this->getDoctrine()->getManager();
            $em->flush();
        }

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/show/{id}", name="show_task")
     *
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showTaskAction($id) {
        $task = $this->findTaskById($id);

        return $this->render('tasks/show.html.twig', array(
            'task' => $task
        ));
    }

    /**
     * @Route("/complete/{id}", name="complete_task")
     *
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @internal param Request $request
     */
    public function completeTaskAction($id) {
        $task = $this->findTaskById($id);

        $task->setCompleted(true);

        $em = $this->getDoctrine()->getManager();
        $em->flush();
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/delete", name="delete_task")
     * @Method({"POST"})
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function deleteTaskAction(Request $request) {
        $requestData = $request->request->get('task');

        if ($this->isCsrfTokenValid($this->getUserToken(), $requestData['_token'])) {
            $task = $this->findTaskById($requestData['id']);

            $em = $this->getDoctrine()->getManager();
            $em->remove($task);
            $em->flush();
        }

        return $this->redirectToRoute('home');
    }

    /**
     * Find Task by ID
     *
     * @param $id
     * @return Task|null|object
     */
    private function findTaskById($id) {
        return $this->getDoctrine()->getRepository('AppBundle:Task')->find($id);
    }

    /**
     * Get the security token from the current user
     *
     * @return null|\Symfony\Component\Security\Core\Authentication\Token\TokenInterface
     */
    private function getUserToken() {
        return $this->get('security.token_storage')->getToken();
    }
}
