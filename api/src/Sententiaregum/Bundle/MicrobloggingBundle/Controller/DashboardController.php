<?php

namespace Sententiaregum\Bundle\MicrobloggingBundle\Controller;

use Sententiaregum\Bundle\UserBundle\Entity\Api\UserInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\SecurityContextInterface;

class DashboardController
{
    /**
     * @var SecurityContextInterface
     */
    private $securityContext;

    public function __construct(SecurityContextInterface $securityContext)
    {
        $this->securityContext = $securityContext;
    }

    public function indexAction()
    {
        return new JsonResponse();
    }

    public function createAction(Request $request)
    {
        $inputData = json_decode($request->getContent(), true);

        $user = $this->securityContext->getToken()->getUser();
        if (!$user instanceof UserInterface) {
            throw new UnsupportedUserException;
        }

        $entryContent = \igorw\get_in($inputData, ['content']);
        /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $image */
        $image = $request->files->get('appended-image');


    }
}
