<?php

namespace Sententiaregum\Bundle\WebBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
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
     * @ApiDoc(
     *     description="This action renders the layout of the frontend",
     *     statusCodes={
     *         200="The layout was rendered successfully"
     *     }
     * )
     */
    public function layoutAction()
    {
    }

    /**
     * Renders a form by its alias
     *
     * @param string $alias
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Method({"GET"})
     * @Route(
     *     "/form/{alias}",
     *     requirements={"alias":"\w+"},
     *     defaults={"_format":"html"},
     *     name="sen_web_form"
     * )
     * @ApiDoc(
     *     description="This action renders a symfony form",
     *     statusCodes={
     *         200="Returned when form was loaded successfully",
     *         404="Returned when form cannot be found"
     *     },
     *     requirements={
     *         {
     *             "name"="alias",
     *             "dataType"="string",
     *             "requirement"="\w+",
     *             "description"="The alias of the form"
     *         }
     *     }
     * )
     */
    public function renderSymfonyFormAction($alias)
    {

    }
}
