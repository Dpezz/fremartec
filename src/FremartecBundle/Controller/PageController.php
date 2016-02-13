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
     * @Route("/email/{email}/{id}")
     * @Method("POST")
     */
    public function email(Request $request, $email, $id){
        try{
            $title = $this->find($id);
            $this->store($email);
            
            return new Response('download/'.$title.'/'.$id);
        }catch(Exception $e){
        }
        
        return new Response(0);
    }

    private function find($id){
        $url = "files/files.json";
        $file = file_get_contents($url);
        $json = json_decode($file,true);
        foreach ($json['files'] as $key => $value) {
            if($value['id'] == $id){
                return $value['title'];
            }
        }
        return -1;
    }

    private function store($email){
        $url = "files/email.json";
        $file = file_get_contents($url);
        $json = json_decode($file,true);
        $json['emails'][] = array('email'=>$email);
        $json = json_encode($json,true);
        file_put_contents($url, $json);
    }

    /**
     * @Route("/download/{title}/{id}", name="download")
     * @Method("GET")
     */
    public function download(Request $request,$title,$id){
        //$path = $this->get('kernel')->getRootDir(). '/../web/files/'.$id.'.pdf';
        $path = $this->get('kernel')->getRootDir(). '/web/files/'.$id.'.pdf';
        //$path = $entity->getAbsolutePath();
        $path = preg_replace("/app..../i", "", $path);
        $content = file_get_contents($path,true);

        $type = explode('.', $path);
        $response = new Response();
        $response->headers->set('Content-Type', 'application/'.$type[1]);
        $response->headers->set('Content-Disposition',
         'attachment;filename='.$title.'.'.$type[1]);
        $response->setContent($content);

        return $response;
    }
}
