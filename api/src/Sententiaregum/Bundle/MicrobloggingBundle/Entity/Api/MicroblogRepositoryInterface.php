<?php

namespace Sententiaregum\Bundle\MicrobloggingBundle\Entity\Api;

use Sententiaregum\Bundle\MicrobloggingBundle\Entity\MicroblogEntry;
use Symfony\Component\HttpFoundation\File\File;

interface MicroblogRepositoryInterface
{
    /**
     * @param $content
     * @param $userId
     * @param \DateTime $creationDate
     * @param File $uploadedFile
     * @return MicroblogEntry
     */
    public function create($content, $userId,  \DateTime $creationDate, File $uploadedFile = null);

    /**
     * @param MicroblogEntry $microblogEntry
     * @return MicroblogEntry
     */
    public function add(MicroblogEntry $microblogEntry);

    /**
     * @return void
     */
    public function flush();

    /**
     * @param integer $entryId
     * @return boolean
     */
    public function existsById($entryId);
}
