<?php

namespace SodaStorageSystem\OrderManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('SodaStorageSystemOrderManagerBundle:Default:index.html.twig', array('name' => $name));
    }
}
