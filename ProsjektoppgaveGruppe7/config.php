<?php


$host = "kark.uit.no";
$dbname = "stud_v18_poroshin";
$username = "stud_v18_poroshin";
$password = "311080Bel";

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
} catch (PDOException $e) {

    print($e->getMessage());
}

$sitename = "Blogg";

require_once "vendor/autoload.php";
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader, array());