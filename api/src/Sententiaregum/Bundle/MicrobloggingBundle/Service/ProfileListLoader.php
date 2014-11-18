<?php

namespace Sententiaregum\Bundle\MicrobloggingBundle\Service;

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
}
 