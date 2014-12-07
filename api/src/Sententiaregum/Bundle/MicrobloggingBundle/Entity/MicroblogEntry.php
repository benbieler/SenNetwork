<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\MicrobloggingBundle\Entity;

use Sententiaregum\Bundle\CommentBundle\Entity\Comment;
use Sententiaregum\Bundle\HashtagsBundle\Entity\Tag;
use Sententiaregum\Bundle\RedisMQBundle\Entity\QueueEntity;
use Symfony\Component\HttpFoundation\File\File;

class MicroblogEntry implements \JsonSerializable
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $authorId;

    /**
     * @var string
     */
    private $content;

    /**
     * @var \DateTime
     */
    private $creationDate;

    /**
     * @var \Symfony\Component\HttpFoundation\File\File
     */
    private $uploadedImage;

    /**
     * @var string
     */
    private $imagePath;

    /**
     * @var Comment[]
     */
    private $comments = [];

    /**
     * @var string[]
     */
    private $marked = [];

    /**
     * @var \Sententiaregum\Bundle\HashtagsBundle\Entity\Tag[]
     */
    private $tags = [];

    /**
     * @return integer
     */
    public function getAuthorId()
    {
        return $this->authorId;
    }

    /**
     * @param integer $authorId
     * @return $this
     */
    public function setAuthorId($authorId)
    {
        $this->authorId = (integer) $authorId;
        return $this;
    }

    /**
     * @return Comment[]
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param Comment[] $comments
     * @return $this
     */
    public function setComments(array $comments)
    {
        array_map(function ($element) {
            if (!$element instanceof Comment) {
                throw new \InvalidArgumentException(sprintf('All comments must be type of %s!', Comment::class));
            }
        }, $comments);

        $this->comments = $comments;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = (string) $content;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * @param \DateTime $creationDate
     * @return $this
     */
    public function setCreationDate(\DateTime $creationDate)
    {
        $this->creationDate = $creationDate;
        return $this;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param integer $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = (integer) $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getImagePath()
    {
        return $this->imagePath;
    }

    /**
     * @param string $imagePath
     * @return $this
     */
    public function setImagePath($imagePath)
    {
        $this->imagePath = (string) $imagePath;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getMarked()
    {
        return $this->marked;
    }

    /**
     * @param string[] $marked
     * @return $this
     */
    public function setMarked(array $marked)
    {
        $this->marked = $marked;
        return $this;
    }

    /**
     * @return \Sententiaregum\Bundle\HashtagsBundle\Entity\Tag[]
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param \Sententiaregum\Bundle\HashtagsBundle\Entity\Tag[] $tags
     * @return $this
     */
    public function setTags(array $tags)
    {
        array_map(function ($tag) {
            return $tag instanceof Tag;
        }, $tags);

        $this->tags = $tags;
        return $this;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\File\File
     */
    public function getUploadedImage()
    {
        return $this->uploadedImage;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\File $uploadedImage
     * @return $this
     */
    public function setUploadedImage(File $uploadedImage = null)
    {
        $this->uploadedImage = $uploadedImage;
        return $this;
    }

    /**
     * @return mixed[]
     */
    public function jsonSerialize()
    {
        $comments = [];
        foreach ($this->comments as $comment) {
            $comments[] = [
                'id' => $comment->getId(),
                'authorId' => $comment->getAuthorId(),
                'content' => $comment->getContent(),
                'date' => $comment->getCreationDate()
            ];
        }

        return [
            'entry_id' => $this->id,
            'content' => $this->content,
            'appendedImagePath' => $this->getImagePath(),
            'authorId' => $this->authorId,
            'comments' => $comments,
            'creationDate' => $this->creationDate
        ];
    }

    public function toMessageQueue()
    {
        return new QueueEntity($this->jsonSerialize());
    }
}
