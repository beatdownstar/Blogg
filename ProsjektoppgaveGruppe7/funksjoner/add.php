<?php

if (isset($_POST['OppretteBlog'])) {

    $newblog = new objBlog();

    $newblog->setBlogName(filter_input(INPUT_POST, "blogname", FILTER_SANITIZE_SPECIAL_CHARS));
    $newblog->setBlogCategory(filter_input(INPUT_POST, "idBlogCategory", FILTER_SANITIZE_SPECIAL_CHARS));
    $newblog->setIdUser($_SESSION['id_user']);
    $newblog->setAboutBlogger(filter_input(INPUT_POST, "aboutblogger", FILTER_SANITIZE_SPECIAL_CHARS));

    $blogblog = $blogdb->creatingBlog($newblog);
}


if (isset($_POST['publiser'])) {

    $post = new Posts();

    $post->setTittle(filter_input(INPUT_POST, "tittle", FILTER_SANITIZE_SPECIAL_CHARS));
    $post->setIdCategory(filter_input(INPUT_POST, "categ", FILTER_VALIDATE_INT));
    $post->setKeyword(filter_input(INPUT_POST, "keyword", FILTER_SANITIZE_SPECIAL_CHARS));
    $post->setText(filter_input(INPUT_POST, "text", FILTER_SANITIZE_SPECIAL_CHARS));
    $post->setIdBlog($_SESSION['id_blog']);
    $_SESSION['publisere'] = 1;

    $errors = array();
    $maxsize = 2097152;
    $acceptable = array(
        'image/jpeg',
        'image/jpg',
        'image/gif',
        'image/png'
    );

    if (($_FILES['photo']['size'] >= $maxsize) or ($_FILES['photo']['size'] == 0)) {
        $errors[] = 'File too large. File must be less than 2 megabytes.';
    }

    if ((!in_array($_FILES['photo']['type'], $acceptable)) and (!empty($_FILES['photo']['type']))) {
        $errors[] = 'Invalid file type. Only PDF, JPG, GIF and PNG types are accepted.';
    }

    if (count($errors) == 0) {
        $post->setPhoto(file_get_contents($_FILES['photo']['tmp_name']));
    } else {
        foreach ($errors as $error) {
            echo '<script>alert("' . $error . '");</script>';
        }

        die(); //Ensure no more processing is done
    }
    $test1 = $blogdb->newPost($post);
    $_GET['idblog'] = $_SESSION['id_blog'];
}

if (isset($_POST['addcomment'])) {

    $newcomment = new Comments();

    if (!empty($_POST['commentcommentID'])) {
        $newcomment->setCommentCommentID($_POST['commentcommentID']);
        $newcomment->setText($_POST['commentanswer']);
        $newcomment->setIdUser($_SESSION['id_user']);
        $newcomment->setIdPost($_GET['idpost']);

    } else {

        $newcomment->setCommentCommentID(NULL);

        $newcomment->setText(filter_input(INPUT_POST, "newcomment", FILTER_SANITIZE_SPECIAL_CHARS));
        $newcomment->setIdUser($_SESSION['id_user']);
        $newcomment->setIdPost(filter_input(INPUT_GET, "idpost", FILTER_VALIDATE_INT));

    }
    $blogdb->addNewComment($newcomment);
}
