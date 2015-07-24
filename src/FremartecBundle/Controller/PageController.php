<?php

namespace FremartecBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use FremartecBundle\Entity\Emails;


class PageController extends Controller
{
	/**
     * @Route("/", name="home")
     * @Method("GET")
     * @Template()
     */
    public function homeAction()
    {
        return array();
    }

	/**
     * @Route("/comercial", name="comercial")
     * @Method("GET")
     * @Template()
     */
    public function comercialAction()
    {
        return array();
    }

	/**
     * @Route("/ingenieria", name="ingenieria")
     * @Method("GET")
     * @Template()
     */
    public function ingenieriaAction()
    {
        return array();
    }

	/**
     * @Route("/consultora", name="consultora")
     * @Method("GET")
     * @Template()
     */
    public function consultoraAction()
    {
        return array();
    }


    /**
     * @Route("/email")
     * @Method("POST")
     */
    public function email(Request $request){
        $data = json_decode($request->getContent(), true);
        $request->request->replace($data);

        try{
            $email = $request->get('email');
            $title = $request->get('title');
            $category = $request->get('category');

            $em = $this->getDoctrine()->getManager();
            if(!$em->getRepository('FremartecBundle:Emails')->findBy(array('email'=>$email))) {
                $user = new Emails();
                $user->setEmail($email)
                ->setCreateAt(new \DateTime('now'));

                //$em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
            }
            //$this->download($title,$category);
            return new Response('/download/'.$title.'/'.$category);
        }catch(Exception $e){
        }
        
        return new Response(0);
    }

    /**
     * @Route("/download/{title}/{url}", name="download")
     * @Method("GET")
     */
    public function download(Request $request,$title,$url){
        $em = $this->getDoctrine()->getManager();
        if( $doc = $em->getRepository('FremartecBundle:Documents')->findOneBy(array('title'=>$title,'category'=>$url)))
        {
            $path = $this->get('kernel')->getRootDir(). '/../web/files/'.$doc->getId().'.pdf';
            $path = preg_replace("/app..../i", "", $path);
            $content = file_get_contents($path,true);
            $response = new Response();
            $response->headers->set('Content-Type', 'application/pdf');
            $response->headers->set('Content-Disposition',
             'attachment;filename='.$title.'_'.$url.'.pdf');
            $response->setContent($content);
            return $response;
        }
        return new RedirectResponse($this->generateUrl( trim($url,'/')));
    }
}
