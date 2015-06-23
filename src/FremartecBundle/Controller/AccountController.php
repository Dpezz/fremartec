<?php

namespace FremartecBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use FremartecBundle\Entity\User;


class AccountController extends Controller
{
	/**
     * @Route("/login", name="login")
     * @Method("GET")
     * @Template()
     */
    public function loginAction(Request $request)
    {
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $request->getSession()->get(SecurityContext::AUTHENTICATION_ERROR);
        }
        return array(
            'last_username' => $request->getSession()->get(SecurityContext::LAST_USERNAME),
            'error'         => $error,
        );
    }

    /**
     * @Route("/admin/login_check", name="security_check")
     */
    public function securityCheckAction()
    {
        // The security layer will intercept this request
    }

    /**
     * @Route("/admin/logout", name="logout")
     */
    public function logoutAction()
    {
        // The security layer will intercept this request
    }

    /**
     * @Route("/create", name="create")
     * @Method("GET")
     */
    public function createAction(Request $request)
    {
        try{
            $user = new User();
            $user->setUsername('fremartec')
            ->setEmail('contacto@fremartec.cl')
            ->setPassword('123')
            ->setRole('ROLE_ADMIN')
            ->setCreateAt(new \DateTime('now'));
                   
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }catch(Exception $e){
            return new Response("ERROR");
        }
        return new Response("CREADO");
    }

 }