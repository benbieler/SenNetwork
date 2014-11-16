<?php
namespace Sententiaregum\Bundle\MicrobloggingBundle\Entity\Api;

use Sententiaregum\Bundle\MicrobloggingBundle\Entity\MicroblogEntry;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface MicroblogRepositoryInterface
{
    /**
     * @param $content
     * @param $userId
     * @param UploadedFile $uploadedFile
     * @param \DateTime $creationDate
     * @return MicroblogEntry
     */
    public function create($content, $userId, UploadedFile $uploadedFile, \DateTime $creationDate);

    /**
     * @param MicroblogEntry $microblogEntry
     */
    public function add(MicroblogEntry $microblogEntry);
}
 