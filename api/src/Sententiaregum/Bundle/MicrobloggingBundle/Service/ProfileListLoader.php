<?php

namespace Sententiaregum\Bundle\MicrobloggingBundle\Service;

use Sententiaregum\Bundle\MicrobloggingBundle\Entity\MicroblogEntry;
use Sententiaregum\Bundle\MicrobloggingBundle\Service\Api\EntryListLoaderInterface;

class ProfileListLoader implements EntryListLoaderInterface
{
    use PostViewTrait;

    /**
     * @param string $userName
     * @return mixed[]
     */
    public function generateRawPostListByUser($userName)
    {

    }

    /**
     * @param MicroblogEntry $entry
     * @param integer $userId
     * @return boolean
     */
    public function checkUserShouldReceivePost(MicroblogEntry $entry, $userId)
    {
        # TODO: Implement checkUserShouldReceivePost() method.
    }
}
