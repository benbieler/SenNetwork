<?php
namespace Sententiaregum\Bundle\MicrobloggingBundle\Entity\Api;

use Sententiaregum\Bundle\MicrobloggingBundle\Entity\MicroblogEntry;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface MicroblogRepositoryInterface
{
    /**
     * @param $content
     * @param $userId
     * @param \DateTime $creationDate
     * @param UploadedFile $uploadedFile
     * @return MicroblogEntry
     */
    public function create($content, $userId,  \DateTime $creationDate, UploadedFile $uploadedFile = null);

    /**
     * @param MicroblogEntry $microblogEntry
     */
    public function add(MicroblogEntry $microblogEntry);
}
 