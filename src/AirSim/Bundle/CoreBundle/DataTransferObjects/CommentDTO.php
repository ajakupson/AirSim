<?php

namespace AirSim\Bundle\CoreBundle\DataTransferObjects;


class CommentDTO
{
    /**
     * @var integer
     */
    private $commentId;

    /**
     * @var string
     */
    private $commentText;

    /**
     * @var string
     */
    private $commentDateAdded;

    /**
     * @var integer
     */
    private $authorId;

    /**
     * @var string
     */
    private $authorName;

    /**
     * @var string
     */
    private $authorFamily;

    /**
     * @var string
     */
    private $authorLogin;

    /**
     * @var string
     */
    private $authorWebProfilePic;

    public function expose()
    {
        return get_object_vars($this);
    }

    // Getters / Setters
    /**
     * @param string $authorFamily
     */
    public function setAuthorFamily($authorFamily)
    {
        $this->authorFamily = $authorFamily;
    }

    /**
     * @return string
     */
    public function getAuthorFamily()
    {
        return $this->authorFamily;
    }

    /**
     * @param int $authorId
     */
    public function setAuthorId($authorId)
    {
        $this->authorId = $authorId;
    }

    /**
     * @return int
     */
    public function getAuthorId()
    {
        return $this->authorId;
    }

    /**
     * @param string $authorLogin
     */
    public function setAuthorLogin($authorLogin)
    {
        $this->authorLogin = $authorLogin;
    }

    /**
     * @return string
     */
    public function getAuthorLogin()
    {
        return $this->authorLogin;
    }

    /**
     * @param string $authorName
     */
    public function setAuthorName($authorName)
    {
        $this->authorName = $authorName;
    }

    /**
     * @return string
     */
    public function getAuthorName()
    {
        return $this->authorName;
    }

    /**
     * @param string $authorWebProfilePic
     */
    public function setAuthorWebProfilePic($authorWebProfilePic)
    {
        $this->authorWebProfilePic = $authorWebProfilePic;
    }

    /**
     * @return string
     */
    public function getAuthorWebProfilePic()
    {
        return $this->authorWebProfilePic;
    }

    /**
     * @param string $commentDateAdded
     */
    public function setCommentDateAdded($commentDateAdded)
    {
        $this->commentDateAdded = $commentDateAdded;
    }

    /**
     * @return string
     */
    public function getCommentDateAdded()
    {
        return $this->commentDateAdded;
    }

    /**
     * @param int $commentId
     */
    public function setCommentId($commentId)
    {
        $this->commentId = $commentId;
    }

    /**
     * @return int
     */
    public function getCommentId()
    {
        return $this->commentId;
    }

    /**
     * @param string $commentText
     */
    public function setCommentText($commentText)
    {
        $this->commentText = $commentText;
    }

    /**
     * @return string
     */
    public function getCommentText()
    {
        return $this->commentText;
    }


}