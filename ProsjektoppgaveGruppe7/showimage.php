<?php
require_once "config.php";

spl_autoload_register(function ($class_name) {
    require_once $class_name . '.class.php';
});

$blogdb = new Blog($db);

if (isset($_GET['id_post'])) {
    header("content-type: image/*");
    $photo = $blogdb->showPhoto($_GET['id_post']);
    echo $photo['photo'];
}
