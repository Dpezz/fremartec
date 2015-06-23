<?php

namespace FremartecBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use FremartecBundle\Entity\Documents;

/**
 * @Route("/admin/documents")
 */
class DocumentController extends Controller
{
	/**
     * @Route("/", name="documents")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
    	//Asignar el FLAG
        if(!$request->getSession()->get('flag'))
        {$request->getSession()->set('flag',0);}

        $flag = $request->getSession()->get('flag');
        $request->getSession()->set('flag',0);

        $em = $this->getDoctrine()->getManager();
        $data = $em->getRepository('FremartecBundle:Documents')
        ->findBy(array(),array('createAt'=>'DESC'));
        
        return array('flag'=>$flag,'data'=>$data);
    }

	/**
     * @Route("/new", name="new_document")
     * @Method("GET")
     * @Template()
     */
    public function newAction(Request $request)
    {
        return array();
    }

    /**
     * @Route("/show/{id}", name="show_document")
     * @Method("GET")
     * @Template()
     */
    public function showAction(Request $request, $id)
    {
    	$em = $this->getDoctrine()->getManager();
        $data = $em->getRepository('FremartecBundle:Documents')->find($id);
        return array('data'=>$data);
    }

    /**
     * @Route("/download/{id}", name="download_document")
     * @Method("GET")
     */
    public function downloadAction(Request $request,$id){
        $em = $this->getDoctrine()->getManager();
        $document = $em->getRepository('FremartecBundle:Documents')->find($id);
        $path = $this->get('kernel')->getRootDir(). '/../web/files/'.$id.'.pdf';
        $path = preg_replace("/app..../i", "", $path);
        $content = file_get_contents($path,true);

        $response = new Response();
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition',
         'attachment;filename='.$document->getTitle().'_'.$document->getCategory().'.pdf');
        $response->setContent($content);
        return $response;
    }

    /**
     * @Route("/create", name="create_document")
     * @Method("POST")
     */
    public function create(Request $request)
    {
        try{
            $file = $request->files->get('file');
            if (($file instanceof UploadedFile) && ($file->getError() == '0'))
            {
                if ($file->getSize() < 2000000)
                {
                    $ext = $file->guessExtension();
                    $type = $file->getMimeType();
                    $size = $file->getClientSize();
                    $title = $request->get('title');
                    $category = $request->get('category');
                    
                    $tipo = array('pdf');
                    if( in_array($ext, $tipo) ){
                        if($file->isValid()){
                            $document = new Documents();
                            $document->setCategory($category)
                            ->setTitle($title)
                            ->setDescription($request->get('description'))
                            ->setSize($size)
                            ->setCreateAt(new \DateTime('now'));

                            $em = $this->getDoctrine()->getManager();
                            $em->persist($document);
                            $em->flush();
                            $id = $document->getId();
                            $upload = $file->move('files/',$id.'.'.$ext);
                            $request->getSession()->set('flag',1);
                        }else{
                            $request->getSession()->set('flag',-1);
                        }
                    }else{
                    	/* file no type */
                        $request->getSession()->set('flag',-1);
                    }
                }else{
                	/* file mayor size */
                    $request->getSession()->set('flag',-1);
                }
            } else {
                //Error de file error (0)
             	$request->getSession()->set('flag',-1);
            }
        }catch(Exception $e){
           $request->getSession()->set('flag',-1);
        }
        return new RedirectResponse($this->generateUrl('documents'));
    }

    /**
     * @Route("/{id}/edit", name="edit_document")
     * @Method("POST")
     */
    public function edit(Request $request, $id)
    {
        try{
            $em = $this->getDoctrine()->getManager();
            if($document = $em->getRepository('FremartecBundle:Documents')->find($id))
            {
                $document->setCategory($request->get('category'))
                ->setTitle($request->get('title'))
                ->setDescription($request->get('description'));
                $em->flush();
                $request->getSession()->set('flag',1);
            }
        }catch(Exception $e){
           $request->getSession()->set('flag',-1);
        }
        return new RedirectResponse($this->generateUrl('documents'));
    }

    /**
     * @Route("/{id}/delete", name="delete_document")
     * @Method("POST")
     */
    public function delete(Request $request, $id)
    {
        $data = json_decode($request->getContent(), true);
        $request->request->replace($data);

        try{
            $em = $this->getDoctrine()->getManager();
            if($document = $em->getRepository('FremartecBundle:Documents')->find($id)){
                $url = 'files/'.$document->getTitle().'_'.$document->getCategory().'.pdf';
                if(is_file($url))
                	unlink($url);
                $em->remove($document);
                $em->flush();
                $request->getSession()->set('flag',1);
                return new Response(1);
            }
        }catch(Exception $e){
            $request->getSession()->set('flag',-1);
            return new Response(-1);
        }
    }
 }