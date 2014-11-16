<?php

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
        $this->articleId = (string) $articleId;
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
