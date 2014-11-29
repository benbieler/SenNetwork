<?php

namespace Sententiaregum\Bundle\EntryParsingBundle\Parser;

use Sententiaregum\Bundle\EntryParsingBundle\Parser\Api\EntryPostParserInterface;

class EntryParser implements EntryPostParserInterface
{
    /**
     * @var string
     */
    private $tagDelimiter;

    /**
     * @var string
     */
    private $nameDelimiter;

    /**
     * @var boolean
     */
    private $filterFirstChar;

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
     * @return boolean
     */
    protected function isFilterFirstChar()
    {
        return $this->filterFirstChar;
    }

    /**
     * @return string
     */
    protected function getNameDelimiter()
    {
        return $this->nameDelimiter;
    }

    /**
     * @return string
     */
    protected function getTagDelimiter()
    {
        return $this->tagDelimiter;
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
