<?php

namespace Sententiaregum\Bundle\MicrobloggingBundle\Service;

use Sententiaregum\Bundle\EntryParsingBundle\Parser\Api\EntryPostParserInterface;
use Sententiaregum\Bundle\HashtagsBundle\Entity\Api\TagRepositoryInterface;
use Sententiaregum\Bundle\HashtagsBundle\Entity\Tag;
use Sententiaregum\Bundle\MicrobloggingBundle\Entity\Api\MicroblogRepositoryInterface;
use Sententiaregum\Bundle\MicrobloggingBundle\Entity\MicroblogEntry;
use Sententiaregum\Bundle\MicrobloggingBundle\Service\Api\WriteEntryInterface;
use Sententiaregum\Bundle\RedisMQBundle\Api\QueueInputInterface;
use Sententiaregum\Bundle\RedisMQBundle\Entity\QueueEntity;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class WriteEntry implements WriteEntryInterface
{
    /**
     * @var MicroblogRepositoryInterface
     */
    private $microblogRepositoryInterface;

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

    /**
     * @param MicroblogRepositoryInterface $microblogRepo
     * @param QueueInputInterface $queueInput
     * @param ValidatorInterface $validator
     * @param EntryPostParserInterface $entryPostParser
     * @param TagRepositoryInterface $tagRepository
     */
    public function __construct(
        MicroblogRepositoryInterface $microblogRepo,
        QueueInputInterface $queueInput,
        ValidatorInterface $validator,
        EntryPostParserInterface $entryPostParser,
        TagRepositoryInterface $tagRepository)
    {
        $this->microblogRepositoryInterface = $microblogRepo;
        $this->queueInput = $queueInput;
        $this->validator = $validator;
        $this->entryParser = $entryPostParser;
        $this->tagRepository = $tagRepository;
    }

    /**
     * @param MicroblogEntry $microblogEntry
     * @return string[]
     */
    public function validate(MicroblogEntry $microblogEntry)
    {
        $errors = $this->validator->validate($microblogEntry);
        $stack = [];
        if (count($errors) > 0) {
            /** @var \Symfony\Component\Validator\ConstraintViolationInterface $constraintViolation */
            foreach ($errors as $constraintViolation) {
                $stack[] = $constraintViolation->getMessage();
            }
        }

        return $stack;
    }

    /**
     * @param MicroblogEntry $microblogEntry
     * @return boolean
     */
    public function persist(MicroblogEntry $microblogEntry)
    {
        $marked = $this->entryParser->extractNamesFromPost($microblogEntry->getContent());
        $tags = $this->entryParser->extractTagsFromPost($microblogEntry->getContent());

        $tagRepository = $this->tagRepository;
        array_walk($tags, function (&$tag) use ($tagRepository) {
            $entity = new Tag();
            $entity->setName($tag);

            $tagRepository->add($entity);
            $tag = $entity;
        });

        $microblogEntry->setTags($tags);
        $microblogEntry->setMarked($marked);

        $this->microblogRepositoryInterface->add($microblogEntry);
        $this->queueInput->push(new QueueEntity($microblogEntry));
        $this->queueInput->enqueue();

        return $microblogEntry;
    }
}
