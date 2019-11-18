<?php

interface BlogInterface {

    public function showAllposts() : array ;
    public function showpostsofblogger(int $blogid) : array ;
    public function showPost(int $id) : Posts ;
    public function topFiveposts() : array ;
    public function showPostsInCategory(int $id_category): array;


    public function lastFiveblogs() : array;
    public function showaboutblog(int $id_blog): array;

    public function arkhiv(): array;
    public function arkhivValg(string $datoen): array;

    public function lastFiveComments() : array;
    public function PostsComments(int $id_post) : array;


    public function registrerNyBruker(User $user) : bool ;
    public function acceptere(string $id_ver);
    public function newAccept(string $id_ver);
    public function login(string $username, string $password, string $blogowner) : bool ;
    public function userExists(string $username) : bool ;
    public function emailExists(string $email) : bool ;
    public function Blogowner(string $username): bool;
    public function logout();


    public function creatingBlog (objBlog $newblog) : bool;
    public function newPost(Posts $post) : bool ;
    public function addNewComment(Comments $newcomment): bool;

    public function categories() : array;
    public function blogCategories() : array;
    public function showCategories() : array ;


    public function search(string $string) : array;
    public function showPhoto(int $id_post);
    public function setViews(int $id_post);
    public function tags(): array;


    public function deleteBlog() : bool;
    public function deleteUser(): bool;
    public function deleteAllpostsofUser() : bool;
    public function deletePost(int $id_post) : bool;
    public function deleteComment(int $id_comment) : bool;

}

