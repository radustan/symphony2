<?php

namespace AppBundle\Controller;

use Parse\ParseException;
use Parse\ParseUser;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $this->parseConnect();
        $currentUser = ParseUser::getCurrentUser();
        if ($currentUser) {
            return $this->render('default/index.html.twig', array(
                'message' => 'Welcome, ' . $currentUser->getUsername()
            ));
        } else {
            return $this->render('default/index.html.twig', array(
                'message' => 'Please login first'
            ));
        }
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', array(
            'message' => 'Please login first'
        ));
    }

    /**
     * @Route("/login_homepage", name="login_homepage")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction(Request $request)
    {
        if ($request->getMethod() == $request::METHOD_POST) {
            $username = $request->get('username');
            $password = $request->get('password');
            try{
                $this->parseConnect();
                ParseUser::logIn($username, $password);
                return $this->redirectToRoute('homepage');
            } catch (ParseException $e) {
                $this->addFlash(
                    'notice',
                    $e->getMessage()
                );
                return $this->render('default/login.html.twig');
            }
        } else {
            return $this->render('default/login.html.twig');
        }
    }

    /**
     * @Route("/signup", name="signup_homepage")
     */
    public function signupAction(Request $request)
    {
        if ($request->getMethod() == $request::METHOD_POST) {
            $username = $request->get('username');
            $email = $request->get('email');
            $password = $request->get('password');
            try {
                $this->parseConnect();
                $user = new ParseUser();
                $user->setPassword($password);
                $user->setEmail($email);
                $user->setUsername($username);
                $user->signUp();

                $this->addFlash(
                    'notice',
                    'Signup successful. You can login now'
                );

                return $this->redirectToRoute('login_homepage');
            } catch (ParseException $e) {
                $this->addFlash(
                    'notice',
                    $e->getMessage()
                );
            }
        }

        return $this->render('default/signup.html.twig');
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction(Request $request)
    {
        $this->parseConnect();
        ParseUser::logOut();

        return $this->redirectToRoute('homepage');
    }
}
