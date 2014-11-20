<?php

namespace Sententiaregum\Bundle\HashtagsBundle\Entity\Api;

use Sententiaregum\Bundle\HashtagsBundle\Entity\Tag;

interface TagRepositoryInterface
{
    public function add(Tag $tag);
}
