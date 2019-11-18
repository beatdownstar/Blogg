<?php

class Comments
{
    private $id_comment;
    private $id_post;
    private $id_user;
    private $text;
    private $pub_date;
    private $username;
    private $commentcommentID;
    private $posttittle;


    function __construct()
    {

    }

    function getIdComment()
    {
        return $this->id_comment;
    }

    function getIdPost()
    {
        return $this->id_post;
    }

    function getIdUser()
    {
        return $this->id_user;
    }

    function getPostTittle()
    {
        return $this->posttittle;
    }

    function getText()
    {
        return $this->text;
    }

    function getPubDate()
    {
        return $this->pub_date;
    }

    function getUserName()
    {
        return $this->username;
    }

    function getCommentCommentID()
    {
        return $this->commentcommentID;
    }




    function setText($text)
    {
        $this->text = $text;
    }

    function setPubDate($pub_date)
    {
        $this->text = $pub_date;
    }

    function setIdPost($id_post)
    {
        $this->id_post = $id_post;
    }

    function setIdUser($id_user)
    {
        $this->id_user = $id_user;
    }

    function setCommentCommentID($commentcommentID)
    {
        $this->commentcommentID = $commentcommentID;
    }
}