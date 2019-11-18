<?php

if (isset($_POST['deleteBlog'])) {
    $deleteblog = $blogdb->deleteBlog();
    header("Location:" . $_SERVER['PHP_SELF'] . "?page=profile");
}

if (isset($_POST['deleteUser'])) {
    $deleteUser = $blogdb->deleteUser();
    $blogdb->logout();
    header("Location:" . $_SERVER['PHP_SELF']);
}

if (isset($_POST['deleteAllpostsOfUser'])) {
    $deleteAllpostsofUser = $blogdb->deleteAllpostsofUser();

    header("Location:" . $_SERVER['PHP_SELF'] . "?page=add");
}

if (isset($_POST['deleteComment'])) {
    if ($_SESSION['innlogget']) {
        $id_comment = (filter_input(INPUT_POST, "id_comment", FILTER_VALIDATE_INT));;

        $deleteComment = $blogdb->deleteComment($id_comment);
    }
}

if (isset($_POST['deletePost'])) {

    $id_post = $_GET['idpost'];//защитить

    $deletePost = $blogdb->deletePost($id_post);
    header("Location:" . $_SERVER['PHP_SELF'] . "?page=myposts");
}
