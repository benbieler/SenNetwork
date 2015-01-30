<?php

namespace Sententiaregum\Bundle\WebBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Default controller which contains all the web rendering actions which may be used by any js frontend
 */
class GenericTemplateController extends Controller
{
    /**
     * Renders the layout
     *
     * @Route("/layout", defaults={"_format":"html"})
     * @Method({"GET"})
     * @View()
     */
    public function layoutAction()
    {
    }
}
