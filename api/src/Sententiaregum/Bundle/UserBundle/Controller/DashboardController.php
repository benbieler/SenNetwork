<?php

namespace Sententiaregum\Bundle\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class DashboardController extends Controller
{
    public function showRecentPostsAction()
    {
        return new JsonResponse([]);
    }
}
