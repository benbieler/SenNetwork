<?php

namespace Sententiaregum\Bundle\InfrastructureBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('SententiaregumInfrastructureBundle:Default:index.html.twig', array('name' => $name));
    }
}
