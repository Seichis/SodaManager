<?php

namespace SodaStorageSystem\OrderManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use SodaStorageSystem\OrderManagerBundle\Entity\Order;
use SodaStorageSystem\SodaManagerBundle\Entity\Soda;
use SodaStorageSystem\OrderManagerBundle\Form\OrderType;

class SubmitOrderController extends Controller {

    public function indexAction(){
        $em=$this->getDoctrine()->getManager();
        $sodas=$em->getRepository('SodaStorageSystemSodaManagerBundle:Soda')->findAll();
        return $this->render('SodaStorageSystemOrderManagerBundle:Order:index.html.twig', array('sodas' => $sodas ));
    }

    public function showAction($id){
        /* Create an entity manager to fetch the orders from the database*/
        $em=$this->getDoctrine()->getManager();
        $order = $em->getRepository('SodaStorageSystemOrderManagerBundle:Order')->find($id);

        $delete_form=$this->getForm('delete',$order);
        
        return $this->render('SodaStorageSystemOrderManagerBundle:Order:show.html.twig', array(
            'order' => $order,
            'delete_form'=>$delete_form->createView()));
    }

    public function newAction(){
        $order=new Order();
        $em=$this->getDoctrine()->getManager();
        $sodas=$em->getRepository('SodaStorageSystemSodaManagerBundle:Soda')->findAll();
        $form=$this->getForm('create',$order);
        return $this->render('SodaStorageSystemOrderManagerBundle:Order:new.html.twig', array('form' => $form->createView(),'sodas' => $sodas ));
    }

    public function createAction(Request $request){
        $order=new Order();
        $em=$this->getDoctrine()->getManager();
        $sodas=$em->getRepository('SodaStorageSystemSodaManagerBundle:Soda')->findAll();
        $sodanames=array();
        foreach ($sodas as $soda) {
            array_push($sodanames, $soda->getTitle());
        }
        $form=$this->getForm('create',$order);
        $form->handleRequest($request);
        /**
        * If the validation passes we can use Doctrine to persist the orders 
        *submited through the form into the database using an entity manager the orders
        */ 
        if ($form->isValid() && in_array($order->getTitle(), $sodanames)){
            /* Instantiate entity manager */
            $em=$this->getDoctrine()->getManager();
            $em->persist($order);
            /*Last step -  Use the flush method to save the persisted entities into the database*/
            $em->flush();

            /*show success message to the user, using key value (msg and its value in add method) */
            $this->get('session')->getFlashBag()->add('msg','Your order has been submited');
            return $this->redirect($this->generateUrl('order_show', array('id' => $order->getId())));

        }
            /*show success message to the user, using key value (msg and its value in add method) */
            $this->get('session')->getFlashBag()->add('msg','Something went wrong! Probably the soda is not in our list');
            return $this->render('SodaStorageSystemOrderManagerBundle:Order:new.html.twig', array('form' => $form->createView() ));
    }

    public function deleteAction(Request $request, $id){
        $em=$this->getDoctrine()->getManager();
        $order=$em->getRepository('SodaStorageSystemOrderManagerBundle:Order')->find($id);
        $delete_form=$this->getForm('delete',$order);
        $delete_form->handleRequest($request);

        if ($delete_form->isValid()) {
            $em->remove($order);
            $em->flush();
        }
        $this->get('session')->getFlashBag()->add('msg','Your order has been deleted!');

        return $this->redirect($this->generateUrl('browse_sodas'));
    }



    public function getForm($formType, Order $order){
        switch ($formType) {
            case 'create':
                $form=$this->createForm(new OrderType(),$order, array('action'=>$this->generateUrl('order_create'),'method'=>'POST' ));
                $form->add('submit','submit', array('label' => 'Create Order' ));
                return $form;
                
            case 'delete':
                $form=$this
                ->createFormBuilder()
                ->setAction($this->generateUrl('order_delete', array('id'=>$order->getId())))
                ->setMethod('DELETE')
                ->add('submit','submit',array('label'=>'Delete order'))
                ->getForm();
                return $form;
        }
    }
}