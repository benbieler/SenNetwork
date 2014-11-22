<?php

namespace Sententiaregum\Bundle\MicrobloggingBundle\Controller;

use Sententiaregum\Bundle\MicrobloggingBundle\Entity\Api\MicroblogRepositoryInterface;
use Sententiaregum\Bundle\MicrobloggingBundle\Service\Api\WriteEntryInterface;
use Sententiaregum\Bundle\UserBundle\Entity\Api\UserInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\SecurityContextInterface;

class PostsController
{
    /**
     * @var MicroblogRepositoryInterface
     */
    private $microblogRepo;

    /**
     * @var SecurityContextInterface
     */
    private $securityContext;

    /**
     * @var WriteEntryInterface
     */
    private $writePostService;

    /**
     * @param MicroblogRepositoryInterface $microblogRepo
     * @param WriteEntryInterface $writeEntry
     * @param SecurityContextInterface $securityContext
     */
    public function __construct(
        MicroblogRepositoryInterface $microblogRepo,
        WriteEntryInterface $writeEntry,
        SecurityContextInterface $securityContext)
    {
        $this->microblogRepo = $microblogRepo;
        $this->securityContext = $securityContext;
        $this->writePostService = $writeEntry;
    }

    /**
     * @return JsonResponse
     */
    public function indexAction()
    {
        return new JsonResponse();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
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

        $entity = $this->microblogRepo->create($entryContent, $user->getId(), new \DateTime(), $image);
        $errors = $this->writePostService->validate($entity);
        if (count($errors) > 0) {
            return new JsonResponse(['errors' => $errors]);
        }

        try {
            $this->writePostService->persist($entity);
        } catch (\Exception $ex) {
            return new JsonResponse(['failure' => $ex->getMessage()], 500);
        }

        return new JsonResponse($entity->jsonSerialize());
    }
}
