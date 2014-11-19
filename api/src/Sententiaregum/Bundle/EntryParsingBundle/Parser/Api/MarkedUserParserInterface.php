<?php

namespace Sententiaregum\Bundle\EntryParsingBundle\Parser\Api;

interface MarkedUserParserInterface
{
    /**
     * @param string $delimiter
     * @return $this
     */
    public function setNameDelimiter($delimiter = '@');

    /**
     * @param string $content
     * @return string[]
     */
    public function extractNamesFromPost($content);
}
 