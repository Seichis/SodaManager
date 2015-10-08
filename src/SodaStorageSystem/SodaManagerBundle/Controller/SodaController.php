<?php

namespace SodaStorageSystem\SodaManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use SodaStorageSystem\SodaManagerBundle\Entity\Soda;
use SodaStorageSystem\SodaManagerBundle\Form\SodaType;
use Symfony\Component\HttpFoundation\Response;

class SodaController extends Controller {

    public function indexAction(){
        $em=$this->getDoctrine()->getManager();
        $sodas=$em->getRepository('SodaStorageSystemSodaManagerBundle:Soda')->findAll();
        return $this->render('SodaStorageSystemSodaManagerBundle:Soda:index.html.twig', array('sodas' => $sodas ));
    }

    public function showAction($id){
        /* Create an entity manager to fetch the sodas from the database*/
        $em=$this->getDoctrine()->getManager();
        $soda = $em->getRepository('SodaStorageSystemSodaManagerBundle:Soda')->find($id);

        $delete_form=$this->getForm('delete',$soda);
        
        return $this->render('SodaStorageSystemSodaManagerBundle:Soda:show.html.twig', array(
            'soda' => $soda,
            'delete_form'=>$delete_form->createView()));
    }

    public function newAction(){
        $soda=new Soda();
        /**
        *Create form and the corresponding button. 
        *Also setting the action to lead (using generate url method) to the soda_create url to handle the response
        */
        $form=$this->getForm('create',$soda);
        return $this->render('SodaStorageSystemSodaManagerBundle:Soda:new.html.twig', array('form' => $form->createView() ));
    }

    public function createAction(Request $request){
        $soda=new Soda();
        
        $form=$this->getForm('create',$soda);
        $form->handleRequest($request);
        /**
        * If the validation passes we can use Doctrine to persist the sodas 
        *submited through the form into the database using an entity manager the sodas
        */ 
        if ($form->isValid()){
            /* Instantiate entity manager */
            $em=$this->getDoctrine()->getManager();
            $em->persist($soda);
            /*Last step -  Use the flush method to save the persisted entities into the database*/
            $em->flush();

            /*show success message to the user, using key value (msg and its value in add method) */
            $this->get('session')->getFlashBag()->add('msg','Your soda has been created');
            return $this->redirect($this->generateUrl('soda_show', array('id' => $soda->getId())));

        }
            /*show success message to the user, using key value (msg and its value in add method) */
            $this->get('session')->getFlashBag()->add('msg','Something went wrong!');
            return $this->render('SodaStorageSystemSodaManagerBundle:Soda:new.html.twig', array('form' => $form->createView() ));
    }

    public function editAction($id){
        #Find the soda, create the form and then render it
        $em=$this->getDoctrine()->getManager();
        $soda=$em->getRepository('SodaStorageSystemSodaManagerBundle:Soda')->find($id);

        $form=$this->getForm('edit',$soda);
        return $this->render('SodaStorageSystemSodaManagerBundle:Soda:edit.html.twig', array('form'=>$form->createView() ));
    }

    public function updateAction(Request $request, $id){
        #Find the soda, create the form and then render it
        $em=$this->getDoctrine()->getManager();
        $soda=$em->getRepository('SodaStorageSystemSodaManagerBundle:Soda')->find($id);
        $form=$this->getForm('edit',$soda);
        $form->handleRequest($request);
        if ($form->isValid()){
            $em->flush();
            $this->get('session')->getFlashBag()->add('msg','Your soda has been updated');
            return $this->redirect($this->generateUrl('soda_show', array('id' => $id )));
        }

        return $this->render('SodaStorageSystemSodaManagerBundle:Soda:edit.html.twig', array('form'=>$form->createView() ));
    }

    public function deleteAction(Request $request, $id){
        $em=$this->getDoctrine()->getManager();
        $soda=$em->getRepository('SodaStorageSystemSodaManagerBundle:Soda')->find($id);
        $delete_form=$this->getForm('delete',$soda);
        $delete_form->handleRequest($request);

        if ($delete_form->isValid()) {
            $em->remove($soda);
            $em->flush();
        }
        $this->get('session')->getFlashBag()->add('msg','Your soda has been deleted!');

        return $this->redirect($this->generateUrl('soda'));
    }


    public function getForm($formType, Soda $soda){
        switch ($formType) {
            case 'create':
                $form=$this->createForm(new SodaType(),$soda, array('action'=>$this->generateUrl('soda_create'),'method'=>'POST' ));
                $form->add('submit','submit', array('label' => 'Create Soda' ));
                return $form;
                
            case 'edit':
                $form=$this->createForm(new SodaType(),$soda, array(
                'action'=>$this->generateUrl('soda_update', array('id'=>$soda->getId() )), 'method'=>'PUT' ));
                $form->add('submit','submit', array('label' => 'Update soda' ));
                return $form;
                
            case 'delete':
                $form=$this
                ->createFormBuilder()
                ->setAction($this->generateUrl('soda_delete', array('id'=>$soda->getId())))
                ->setMethod('DELETE')
                ->add('submit','submit',array('label'=>'Delete soda'))
                ->getForm();
                return $form;
        }
    }
}