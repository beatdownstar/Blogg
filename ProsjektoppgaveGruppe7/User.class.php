<?php

class User
{
    private $id_user;
    private $username;
    private $password;
    private $email;
    private $id_blog;
    private $blogname;
    private $id_ver;
    private $accept;


    function __construct()
    {

    }

    function getId()
    {
        return $this->id_user;
    }

    function getUsername()
    {
        return $this->username;
    }

    function getEmail()
    {
        return $this->email;
    }

    function getPassword()
    {
        return $this->password;
    }

    function getBlogid()
    {
        return $this->id_blog;
    }

    function getBlogname()
    {
        return $this->blogname;
    }

    function getIdVer()
    {
        return $this->id_ver;
    }

    function getAccept()
    {
        return $this->accept;
    }




    function setUsername($username)
    {
        $this->username = $username;
    }

    function setEmail($email)
    {
        $this->email = $email;
    }

    function setPassword($password)
    {
        $this->password = $password;
    }

    function setIdVer($id_ver)
    {
        $this->id_ver = $id_ver;
    }

    function setAccept($accept)
    {
        $this->accept = $accept;
    }


}
