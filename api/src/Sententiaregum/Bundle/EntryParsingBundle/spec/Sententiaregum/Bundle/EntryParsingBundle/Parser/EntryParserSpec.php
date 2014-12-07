<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace spec\Sententiaregum\Bundle\EntryParsingBundle\Parser;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EntryParserSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Sententiaregum\Bundle\EntryParsingBundle\Parser\EntryParser');
    }

    function it_extracts_hashtags()
    {
        $post = <<<POST
Any long
post with #hash #tags
POST;

        $this->extractTagsFromPost($post)->shouldHaveCount(2);
    }

    function it_extracts_names()
    {
        $post = <<<POST
Any else post with content
containing @names
POST;

        $this->extractNamesFromPost($post)->shouldHaveCount(1);
    }

    function it_can_disable_strip_of_the_name_or_tag_delimiter()
    {
        $this->beConstructedWith('#', '@', false);
        $post = <<<POST
Simple #post @name
POST;
        $this->extractTagsFromPost($post)->shouldNotStripDelimiter('#');
        $this->extractNamesFromPost($post)->shouldNotStripDelimiter('@');
    }

    public function getMatchers()
    {
        return [
            'stripDelimiter' => function (array $item, $delimiter) {
                foreach ($item as $element) {
                    if (!(boolean) preg_match('/^(' . $delimiter . '\w+)$/', $element)) {
                        return true;
                    }
                }

                return false;
            }
        ];
    }
}
