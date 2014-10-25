<?php
namespace Ma27\SocialNetworkingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class DashboardController extends Controller
{
    public function showRecentPostsAction()
    {
        return new JsonResponse([]);
    }
}
