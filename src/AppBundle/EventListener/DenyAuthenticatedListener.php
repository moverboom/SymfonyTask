<?php

namespace AppBundle\EventListener;

use Symfony\Component\HttpFoundation\Request;
use AppBundle\Controller\DenyAuthenticatedController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

/**
 * Listens to the FilterControllerEvent
 * When the event is fired, it checks if the controller is an instance of DenyAuthenticatedController
 * Controllers of this instance should not be accessed by authenticated users
 *
 * Class DenyAuthenticatedListener
 * @package AppBundle\EventListener
 */
class DenyAuthenticatedListener
{
    private $authChecker;

    public function __construct(AuthorizationChecker $authorizationChecker) {
        $this->authChecker = $authorizationChecker;
    }

    public function onKernelController(FilterControllerEvent $filterControllerEvent) {
        $controller = $filterControllerEvent->getController();

        /*
         * getController() can return a Closure or array
         * If it is a class, it comes in array format
         */
        if(!is_array($controller)) {
            return;
        }

        if($controller[0] instanceof DenyAuthenticatedController) {
            if($this->authChecker->isGranted('IS_AUTHENTICATED_FULLY')) {

                $redirectUrl = '/';

                $filterControllerEvent->setController(function() use ($redirectUrl) {
                    return new RedirectResponse($redirectUrl);
                });
            }
        }
    }
}