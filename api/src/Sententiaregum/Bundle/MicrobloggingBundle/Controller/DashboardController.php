<?php

namespace Sententiaregum\Bundle\MicrobloggingBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

class DashboardController
{
    public function indexAction()
    {
        return new JsonResponse();
    }
}
