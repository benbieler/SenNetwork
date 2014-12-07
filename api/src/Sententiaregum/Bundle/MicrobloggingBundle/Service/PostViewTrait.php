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

trait PostViewTrait
{
    /**
     * @var \Sententiaregum\Bundle\MicrobloggingBundle\Entity\MicroblogRepository
     */
    private $microblogRepository;

    /**
     * @param mixed[] $postData
     * @return \Sententiaregum\Bundle\MicrobloggingBundle\Entity\MicroblogEntry
     */
    public function createPostView(array $postData)
    {

    }
}
