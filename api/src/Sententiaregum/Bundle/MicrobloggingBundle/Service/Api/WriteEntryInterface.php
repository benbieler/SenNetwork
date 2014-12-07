<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\MicrobloggingBundle\Service\Api;

use Sententiaregum\Bundle\MicrobloggingBundle\Entity\MicroblogEntry;

interface WriteEntryInterface
{
    /**
     * @param MicroblogEntry $microblogEntry
     * @return string[]
     */
    public function validate(MicroblogEntry $microblogEntry);

    /**
     * @param MicroblogEntry $microblogEntry
     * @return MicroblogEntry
     */
    public function persist(MicroblogEntry $microblogEntry);
}
