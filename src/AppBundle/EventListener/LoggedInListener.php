<?php

namespace AppBundle\EventListener;

use AppBundle\Controller\LoggedInInterface;
use AppBundle\Parse\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class LoggedInListener
{
    private $router;
    private $container;

    public function __construct($router, $container)
    {
        $this->router = $router;
        $this->container = $container;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $loginRoute = "login_homepage";
        $nonLoginRoutes = array('homepage', 'signup_homepage', 'login_homepage');
        $routeName = $this->container->get('request')->get('_route');
        if (!in_array($routeName, $nonLoginRoutes)) {
            /** @var User $user */
            $user = $this->container->get('parse.user');
            if (!$user->isLoggedIn()) {
                $routeName = $this->container->get('request')->get('_route');
                if ($routeName != $loginRoute) {
                    $this->container->get('parse.user');
                    $url = $this->router->generate('homepage');
                    $event->setResponse(new RedirectResponse($url));
                }
            }
        }
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        /*
         * $controller passed can be either a class or a Closure.
         * This is not usual in Symfony but it may happen.
         * If it is a class, it comes in array format
         */
        if (!is_array($controller)) {
            return;
        }

        if ($controller[0] instanceof LoggedInInterface) {
            /** @var User $user */
            $user = $controller[0]->get('parse.user');
            if (!$user->isLoggedIn()) {
                throw new AccessDeniedHttpException('Unauthorized');
            }
        }
    }
}
