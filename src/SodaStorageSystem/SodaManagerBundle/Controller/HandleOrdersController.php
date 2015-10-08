<?php

namespace SodaStorageSystem\SodaManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use SodaStorageSystem\OrderManagerBundle\Entity\Order;
use SodaStorageSystem\SodaManagerBundle\Entity\Soda;
use SodaStorageSystem\SodaManagerBundle\Form\SodaType;
use SodaStorageSystem\OrderManagerBundle\Form\OrderType;
use Symfony\Component\HttpFoundation\Response;

class HandleOrdersController extends Controller {

    public function show_ordersAction(){
        $em=$this->getDoctrine()->getManager();
        $orders=$em->getRepository('SodaStorageSystemOrderManagerBundle:Order')->findAll();
        return $this->render('SodaStorageSystemSodaManagerBundle:Soda:show_orders.html.twig', array('orders' => $orders ));
    }

    public function single_orderAction($id){
        /* Create an entity manager to fetch the sodas from the database*/
        $em=$this->getDoctrine()->getManager();
        $order = $em->getRepository('SodaStorageSystemOrderManagerBundle:Order')->find($id);
        # A button to accept the order
        $reply_form_accept=$this->getForm('accept',$order);
        # A button to decline the order
        $reply_form_decline=$this->getForm('decline',$order);

        return $this->render('SodaStorageSystemSodaManagerBundle:Soda:reply_order.html.twig', array(
            'order' => $order,
            'reply_form_accept'=>$reply_form_accept->createView(),
            'reply_form_decline'=>$reply_form_decline->createView()));
    }

    public function declineAction(Request $request, $id){
        $em=$this->getDoctrine()->getManager();
        $order=$em->getRepository('SodaStorageSystemOrderManagerBundle:Order')->find($id);
        $reply_form=$this->getForm('decline',$order);
        $reply_form->handleRequest($request);

        if ($reply_form->isValid()) {
            $em->remove($order);
            $em->flush();
        }
        $this->get('session')->getFlashBag()->add('msg','The order has been declined!');

        return $this->redirect($this->generateUrl('show_orders'));
    }

    public function acceptAction(Request $request, $id){
    	$sodatype=new Soda();
        $em=$this->getDoctrine()->getManager();
        $order=$em->getRepository('SodaStorageSystemOrderManagerBundle:Order')->find($id);
        $sodatype=$em->getRepository('SodaStorageSystemSodaManagerBundle:Soda')->findOneBy(array('title'=>$order->getTitle()));
        
        if (isset($sodatype)){
            # Get the quantities
            $q_ord=$order->getQuantity();
            $q_store=$sodatype->getQuantity();
            $q_final=$q_store-$q_ord;
            #Check if there are enough sodas for the order
            if ($q_final>=0) {
        	   $sodatype->setQuantity($q_final);
        	   $reply_form=$this->getForm('accept',$order);
        	   $reply_form->handleRequest($request);

        	   if ($reply_form->isValid()) {
            	   $em->remove($order);
            	   $em->flush();
        	   }
            }else{
        	   $this->get('session')->getFlashBag()->add('msg','There are not enough sodas!!!!Get some more and come back again');
               return $this->redirect($this->generateUrl('show_orders'));
            }
            $this->get('session')->getFlashBag()->add('msg','The order has been Accepted!');
        }else{
            $this->get('session')->getFlashBag()->add('msg','No such soda!!!');
        }
        return $this->redirect($this->generateUrl('show_orders'));
    }

    public function getForm($formType, Order $order){
        switch ($formType) {

            case 'accept':             
                $form=$this
                ->createFormBuilder()
                ->setAction($this->generateUrl('accept_order', array('id'=>$order->getId())))
                ->setMethod('PUT')
                ->add('submit','submit',array('label'=>'Accept order'))
                ->getForm();
                return $form;

            case 'decline':
                $form=$this
                ->createFormBuilder()
                ->setAction($this->generateUrl('decline_order', array('id'=>$order->getId())))
                ->setMethod('DELETE')
                ->add('submit','submit',array('label'=>'Decline order'))
                ->getForm();
                return $form;
        }
    }
}