<?php

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
 