<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\EntryParsingBundle\Parser\Api;

interface HashtagParserInterface
{
    /**
     * @param string $tagDelimiter
     * @return $this
     */
    public function setHashtagDelimiter($tagDelimiter = '#');

    /**
     * @param string $postContent
     * @return string[]
     */
    public function extractTagsFromPost($postContent);
}
