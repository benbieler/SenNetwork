<?php

namespace Sententiaregum\Bundle\MicrobloggingBundle\Service\Api;

use Sententiaregum\Bundle\MicrobloggingBundle\Entity\MicroblogEntry;

interface EntryListLoaderInterface
{
    /**
     * @param string $userName
     * @return mixed[]
     */
    public function generateRawPostListByUser($userName);

    /**
     * @param mixed[] $postData
     * @return \Sententiaregum\Bundle\MicrobloggingBundle\Entity\MicroblogEntry
     */
    public function createPostView(array $postData);

    /**
     * @param MicroblogEntry $entry
     * @param integer $userId
     * @return boolean
     */
    public function checkUserShouldReceivePost(MicroblogEntry $entry, $userId);
}
