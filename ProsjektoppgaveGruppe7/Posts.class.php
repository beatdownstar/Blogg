<?php

class Posts
{
    private $id_post;
    private $id_blog;
    private $id_category;
    private $tittle;
    private $text;
    private $keyword;
    private $pub_date;
    private $views;
    private $photo;
    private $antallcomments;
    private $categoryname;
    private $username;


    function __construct()
    {

    }

    function getIdPost()
    {
        return $this->id_post;
    }

    function getIdBlog()
    {
        return $this->id_blog;
    }

    function getIdCategory()
    {
        return $this->id_category;
    }

    function getCategoryName()
    {
        return $this->categoryname;
    }

    function getTittle()
    {
        return $this->tittle;
    }

    function getText()
    {
        return $this->text;
    }

    function getKeyword()
    {
        return $this->keyword;
    }

    function getPubDate()
    {
        return $this->pub_date;
    }

    function getViews()
    {
        return $this->views;
    }

    function getUserName()
    {
        return $this->username;
    }

    function getPhoto()
    {
        return $this->photo;
    }

    function getAntallComments()
    {
        return $this->antallcomments;
    }




    function setIdBlog($id_blog)
    {
        $this->id_blog = $id_blog;
    }

    function setIdCategory($id_category)
    {
        $this->id_category = $id_category;
    }

    function setTittle($tittle)
    {
        $this->tittle = $tittle;
    }

    function setText($text)
    {
        $this->text = $text;
    }

    function setKeyword($keyword)
    {
        $this->keyword = $keyword;
    }

    function setPhoto($photo)
    {
        $this->photo = $photo;
    }

    function setViews($views)
    {
        $this->views = $views;
    }


}