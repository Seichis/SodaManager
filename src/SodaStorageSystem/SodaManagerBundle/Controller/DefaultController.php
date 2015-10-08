<?php

namespace SodaStorageSystem\SodaManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('SodaStorageSystemSodaManagerBundle:Default:index.html.twig', array('name' => $name));
    }
}
