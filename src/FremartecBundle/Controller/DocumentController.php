<?php

namespace FremartecBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\File\UploadedFile;


/**
 * @Route("/admin/documents")
 */
class DocumentController extends Controller
{
    /**
     * Lists all Document entities.
     *
     * @Route("/", name="document")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $url = "files/files.json";
        $file = file_get_contents($url);
        $files = json_decode($file,true);

        return array("entities" => $files["files"]);
    }
    /**
     * Creates a new Document entity.
     *
     * @Route("/", name="document_create")
     * @Method("POST")
     * @Template("FremartecBundle:Document:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $categoria = $request->get('categoria') + 1;
        $posicion = $request->get('posicion') + 1;
        $title = $request->get('name');
        $file = $request->files->get('file');

        $this->uploadAction($categoria, $posicion, $title, $file);
        return new RedirectResponse($this->generateUrl('document'));
    }


    /**
     * Displays a form to create a new Document entity.
     *
     * @Route("/new", name="document_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        return array();
    }

    
    /**
     * Deletes a Document entity.
     *
     * @Route("/{id}/delete", name="document_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {   
        $url = "files/files.json";
        $file = file_get_contents($url);
        $json = json_decode($file,true);

        $path = "files/".$id.".pdf";

        try{
            unset($json['files'][$this->find($json["files"], $id)]);
            if(file_exists($path)){
                unlink($path);
            }
            $json = json_encode($json,true);
            file_put_contents($url, $json);
            return new response("ok");
        }catch(Exception $e){
            return new response("error");
        }
        return new response("error");
    }

    private function find($files, $id){
        foreach ($files as $key => $value) {
            if($value['id'] == $id){
                return $key;
            }
        }
        return -1;
    }

    private function store($categoria, $posicion, $title){
        $url = "files/files.json";
        $file = file_get_contents($url);
        $json = json_decode($file,true);
        $json['files'][] = array('id'=>$categoria.'_'.$posicion,'categoria'=>$categoria,'posicion'=>$posicion,'title'=>$title);
        $json = json_encode($json,true);
        file_put_contents($url, $json);
    }

    private function uploadAction($categoria, $posicion, $title, $file){
        try{
            $url = "files/";

            if (($file instanceof UploadedFile) && ($file->getError() == '0'))
            {
                if ($file->getSize() < 999999999999999999999)
                {
                    $name = $file->getClientOriginalName();
                    $ext = $file->guessExtension();
                    $type = $file->getMimeType();
                    $size = $file->getClientSize();
                    
                    $office = array('pdf');
                    if( in_array($ext, $office) ){
                        if($file->isValid()){
                            $upload = $file->move($url,$categoria.'_'.$posicion.'.'.$ext);
                            $this->store($categoria, $posicion, $title);
                            return "true";
                        }else{
                            return "false1";
                        }
                    }else{
                        return "false2";
                    }
                }else{
                    return "false3";
                }
            }else{
                return "false4";
            }
        }catch(Exception $e){
          
            return "false5";
                        
        }
        return "false";
        //return new RedirectResponse($this->generateUrl('document'));
    }

    /**
     * Finds and displays a Document entity.
     *
     * @Route("/download/{id}", name="document_download")
     * @Method("GET")
     * @Template()
     */
    public function downloadAction($id)
    {
        $items = explode('-', $id);
        $id = $items[0];
        $title = $items[1];

        $path = $this->get('kernel')->getRootDir(). '/../web/files/'.$id.'.pdf';
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
