<?php

namespace Sententiaregum\Bundle\WebBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Default controller which contains all the web rendering actions which may be used by any js frontend
 */
class GenericTemplateController extends Controller
{
    /**
     * Renders the layout
     *
     * @Route("/layout", defaults={"_format":"html"}, name="sen_web_layout")
     * @Method({"GET"})
     * @Cache(expires="+6 hours", public=true)
     * @View()
     */
    public function layoutAction()
    {
    }
}
