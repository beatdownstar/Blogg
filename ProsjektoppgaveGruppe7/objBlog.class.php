<?php

class objBlog
{

    private $id_blog;
    private $id_user;
    private $blogname;
    private $id_blogcategory;
    private $aboutblogger;
    private $photo;


    function __construct()
    {

    }

    function getBlogid()
    {
        return $this->id_blog;
    }

    function getIdUser()
    {
        return $this->id_user;
    }

    function getBlogName()
    {
        return $this->blogname;
    }

    public function getBlogCategory()
    {
        return $this->id_blogcategory;
    }

    public function getAboutBlogger()
    {
        return $this->aboutblogger;
    }

    public function getPhoto()
    {
        return $this->photo;
    }


    //Setters

    function setIdUser($id_user)
    {
        $this->id_user = $id_user;
    }

    function setBlogName($blogname)
    {
        $this->blogname = $blogname;
    }

    public function setBlogCategory($id_blogcategory)
    {
        $this->id_blogcategory = $id_blogcategory;
    }

    public function setAboutBlogger($aboutblogger)
    {
        $this->aboutblogger = $aboutblogger;
    }

    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }

}
