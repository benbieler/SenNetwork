<?php

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
