<?php

namespace Sententiaregum\Bundle\MicrobloggingBundle\Controller;

use Sententiaregum\Bundle\MicrobloggingBundle\Entity\Api\MicroblogRepositoryInterface;
use Sententiaregum\Bundle\RedisMQBundle\Api\QueueInputInterface;
use Sententiaregum\Bundle\UserBundle\Entity\Api\UserInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class WriteEntryController
{
    /**
     * @var SecurityContextInterface
     */
    private $securityContext;

    /**
     * @var MicroblogRepositoryInterface
     */
    private $microblogRepo;

    /**
     * @var QueueInputInterface
     */
    private $queueInput;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(
        SecurityContextInterface $securityContext,
        MicroblogRepositoryInterface $microblogRepo,
        QueueInputInterface $queueInput,
        ValidatorInterface $validator
    ) {
        $this->securityContext = $securityContext;
        $this->microblogRepo = $microblogRepo;
        $this->queueInput = $queueInput;
        $this->validator = $validator;
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

        $entity = $this->microblogRepo->create($entryContent, $user->getId(), $image, new \DateTime());
        $violations = $this->validator->validate($entity);
        if (count($violations) > 0) {
            $errors = [];
            /** @var \Symfony\Component\Validator\ConstraintViolationInterface $constraintViolation */
            foreach (iterator_to_array($violations) as $constraintViolation) {
                $errors[] = $constraintViolation->getMessage();
            }

            return new JsonResponse(['errors' => $errors]);
        }

        $this->microblogRepo->add($entity);
        $this->queueInput->push($entity->toMessageQueue());
        $this->queueInput->enqueue();

        return new JsonResponse($entity->jsonSerialize());
    }
}
