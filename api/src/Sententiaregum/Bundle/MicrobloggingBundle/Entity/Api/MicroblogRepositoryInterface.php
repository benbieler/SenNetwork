<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

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
