<?php

namespace spec\Sententiaregum\Bundle\MicrobloggingBundle\Service;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sententiaregum\Bundle\EntryParsingBundle\Parser\Api\EntryPostParserInterface;
use Sententiaregum\Bundle\HashtagsBundle\Entity\Api\TagRepositoryInterface;
use Sententiaregum\Bundle\MicrobloggingBundle\Entity\Api\MicroblogRepositoryInterface;
use Sententiaregum\Bundle\MicrobloggingBundle\Entity\MicroblogEntry;
use Sententiaregum\Bundle\RedisMQBundle\Api\QueueInputInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class WriteEntrySpec extends ObjectBehavior
{
    function let(
        MicroblogRepositoryInterface $microblogRepositoryInterface,
        QueueInputInterface $queueInputInterface,
        ValidatorInterface $validatorInterface,
        EntryPostParserInterface $entryPostParserInterface,
        TagRepositoryInterface $tagRepositoryInterface
    ) {
        $this->beConstructedWith(
            $microblogRepositoryInterface,
            $queueInputInterface,
            $validatorInterface,
            $entryPostParserInterface,
            $tagRepositoryInterface
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Sententiaregum\Bundle\MicrobloggingBundle\Service\WriteEntry');
    }

    function it_validates_the_microblog_entity(ValidatorInterface $validatorInterface, MicroblogEntry $entity)
    {
        $violations = new ConstraintViolationList(
            [
                new ConstraintViolation('error message', null, [], null, null, null),
                new ConstraintViolation('other error message', null, [], null, null, null)
            ]
        );
        $validatorInterface->validate($entity)->willReturn($violations);

        $result = $this->validate($entity);
        $result->shouldHaveCount(2);
    }

    function it_persists_microblog_entities(MicroblogRepositoryInterface $microblogRepositoryInterface, MicroblogEntry $entity)
    {
        $microblogRepositoryInterface->add($entity)->shouldBeCalled();
        $this->persist($entity)->shouldHaveType(MicroblogEntry::class);
    }
}
