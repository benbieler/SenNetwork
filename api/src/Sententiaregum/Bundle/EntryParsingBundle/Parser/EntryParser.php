<?php

namespace Sententiaregum\Bundle\EntryParsingBundle\Parser;

use Sententiaregum\Bundle\EntryParsingBundle\Parser\Api\EntryPostParserInterface;

class EntryParser implements EntryPostParserInterface
{
    /**
     * @var string
     */
    protected $tagDelimiter;

    /**
     * @var string
     */
    protected $nameDelimiter;

    /**
     * @var boolean
     */
    protected $filterFirstChar;

    /**
     * @param $tagDelimiter
     * @param $nameDelimiter
     * @param boolean $filterFirstChar
     */
    public function __construct($tagDelimiter = '#', $nameDelimiter = '@', $filterFirstChar = false)
    {
        $this->setHashtagDelimiter($tagDelimiter);
        $this->setNameDelimiter($nameDelimiter);
        $this->filterFirstChar = (boolean) $filterFirstChar;
    }

    /**
     * @param string $tagDelimiter
     * @return $this
     */
    public function setHashtagDelimiter($tagDelimiter = '#')
    {
        $this->tagDelimiter = (string) $tagDelimiter;
        return $this;
    }

    /**
     * @param string $delimiter
     * @return $this
     */
    public function setNameDelimiter($delimiter = '@')
    {
        $this->nameDelimiter = (string) $delimiter;
        return $this;
    }

    /**
     * @param string $postContent
     * @return string[]
     */
    public function extractTagsFromPost($postContent)
    {
        return $this->extractByRegex('/(' . $this->tagDelimiter . '\w+)/', $postContent, $this->tagDelimiter);
    }

    /**
     * @param string $content
     * @return string[]
     */
    public function extractNamesFromPost($content)
    {
        return $this->extractByRegex('/(' . $this->nameDelimiter . '[A-zäöüÄÖÜß0-9_\-\.]+)/', $content, $this->nameDelimiter);
    }

    private function extractByRegex($regex, $entry, $delimiter)
    {
        if (!(boolean) preg_match_all($regex, $entry, $matchList)) {
            return null;
        }
        $matches = $matchList[0];

        if ($this->filterFirstChar) {
            array_walk($matches, function (&$value) use ($delimiter) {
                $value = substr($value, strlen($delimiter));
            });
        }
        return $matches;
    }
}
