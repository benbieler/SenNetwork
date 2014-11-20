<?php

namespace Sententiaregum\Bundle\MicrobloggingBundle\Controller;

use Sententiaregum\Bundle\EntryParsingBundle\Parser\Api\EntryPostParserInterface;
use Sententiaregum\Bundle\HashtagsBundle\Entity\Api\TagRepositoryInterface;
use Sententiaregum\Bundle\HashtagsBundle\Entity\Tag;
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

    /**
     * @var EntryPostParserInterface
     */
    private $entryParser;

    /**
     * @var TagRepositoryInterface
     */
    private $tagRepository;

    public function __construct(
        SecurityContextInterface $securityContext,
        MicroblogRepositoryInterface $microblogRepo,
        QueueInputInterface $queueInput,
        ValidatorInterface $validator,
        EntryPostParserInterface $entryParser,
        TagRepositoryInterface $tagRepository
    ) {
        $this->securityContext = $securityContext;
        $this->microblogRepo   = $microblogRepo;
        $this->queueInput      = $queueInput;
        $this->validator       = $validator;
        $this->entryParser     = $entryParser;
        $this->tagRepository   = $tagRepository;
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
        $violations = $this->validator->validate($entity);
        if (count($violations) > 0) {
            $errors = [];
            /** @var \Symfony\Component\Validator\ConstraintViolationInterface $constraintViolation */
            foreach (iterator_to_array($violations) as $constraintViolation) {
                $errors[] = $constraintViolation->getMessage();
            }

            return new JsonResponse(['errors' => $errors]);
        }

        $tagRepository = $this->tagRepository;
        $tags = $this->entryParser->extractTagsFromPost($entity->getContent());
        array_walk($tags, function (&$value) use ($tagRepository) {
            $tag = new Tag();
            $tag->setName($value);

            $value = $tag;

            $tagRepository->add($value);
        });
        $names = $this->entryParser->extractNamesFromPost($entity->getContent());

        $entity->setMarked($names);
        $entity->setTags($tags);

        $this->microblogRepo->add($entity);
        $this->queueInput->push($entity->toMessageQueue());
        $this->queueInput->enqueue();

        return new JsonResponse($entity->jsonSerialize());
    }
}
