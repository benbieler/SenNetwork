<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\CommentBundle\Entity;

class Comment
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $articleId;

    /**
     * @var string
     */
    private $content;

    /**
     * @var integer
     */
    private $authorId;

    /**
     * @var \DateTime
     */
    private $creationDate;

    /**
     * @return integer
     */
    public function getArticleId()
    {
        return $this->articleId;
    }

    /**
     * @param integer $articleId
     * @return $this
     */
    public function setArticleId($articleId)
    {
        $this->articleId = (integer) $articleId;
        return $this;
    }

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
}
