<?php

namespace Sententiaregum\Bundle\MicrobloggingBundle\Service\Api;

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
}
