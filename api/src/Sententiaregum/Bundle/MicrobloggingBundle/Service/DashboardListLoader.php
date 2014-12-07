<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\MicrobloggingBundle\Service;

use Sententiaregum\Bundle\MicrobloggingBundle\Entity\MicroblogEntry;
use Sententiaregum\Bundle\MicrobloggingBundle\Service\Api\EntryListLoaderInterface;

class DashboardListLoader implements EntryListLoaderInterface
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
