<?php
session_start();
require_once "config.php";

spl_autoload_register(function ($class_name) {
    require_once $class_name . '.class.php';
});

$blogdb = new Blog($db);

require_once "funksjoner/autorisasjon.php";
require_once "funksjoner/add.php";
require_once "funksjoner/delete.php";
require_once "sessionProtection.php";

//Be om arrays som kreves i alle maler
$topfiveposts = $blogdb->topFiveposts();
$lastfivecomments = $blogdb->lastFiveComments();
$lastfiveblogs = $blogdb->lastFiveblogs();
$arkhiv = $blogdb->arkhiv();
$allposts = $blogdb->showAllposts();

//Handling når du trykker på søkeknappen
if (isset($_POST['btSearch'])) {

    $search = $blogdb->search(filter_input(INPUT_POST, "search", FILTER_SANITIZE_SPECIAL_CHARS));

    echo $twig->render('toparticles.twig', array('blogname' => $_SESSION['blogname'],'search' => $search, 'arkhiv' => $arkhiv, 'loginplus' => $_SESSION['loginsuccess'], 'registered' => $_SESSION['registr'], 'lastfiveblogs' => $lastfiveblogs, 'lastfivecomments' => $lastfivecomments, 'topfiveposts' => $topfiveposts, 'allposts' => $allposts, 'blogowner' => $_SESSION['blogowner'], 'innloget' => $_SESSION['innlogget'], 'bloggernavn' => $_SESSION['name']));

    $_SESSION['registr'] = 0;
    $_SESSION['loginsuccess'] = 0;

} else {

//Eldre innlegg aksesseres på månedsbasis
    if (!empty($_GET['month'])) {

        $arkhivvalg = $blogdb->arkhivValg(($_GET['month']));

        echo $twig->render('allusersposts.twig', array('blogname' => $_SESSION['blogname'], 'period' => $_GET['month'],
            'arkhivvalg' => $arkhivvalg, 'arkhiv' => $arkhiv, 'lastfivecomments' => $lastfivecomments, 'topfiveposts' => $topfiveposts,
            'innloget' => $_SESSION['innlogget'], 'bloggernavn' => $_SESSION['name'], 'blogowner' => $_SESSION['blogowner']));

    } else {

//Informasjon om bloggeren, ved å klikke på navnet hans på hovedsiden i listen over blogger
        if (!empty($_GET['idblogabout']) && ctype_digit($_GET['idblogabout'])) {

            $showaboutblog = $blogdb->showaboutblog($_GET['idblogabout']);

            $about = true; //Hjelpevariabel, for å avgjøre hvilken blogger som skal vises

            if ($_SESSION['id_blog'] != $_GET['idblogabout']) {
                $notownerofthisbliog = true; //Hjelpevariabel, for å avgjøre hvilken blogger som skal vises
            } else {
                $notownerofthisbliog = false; //Hjelpevariabel, for å avgjøre hvilken blogger som skal vises
            }

            echo $twig->render('aboutblogger.twig', array('blogname' => $_SESSION['blogname'], 'notownerofthisbliog' => $notownerofthisbliog, 'arkhiv' => $arkhiv, 'about' => $about, 'showaboutblog' => $showaboutblog, 'lastfiveblogs' => $lastfiveblogs, 'lastfivecomments' => $lastfivecomments, 'topfiveposts' => $topfiveposts, 'blogowner' => $_SESSION['blogowner'], 'innloget' => $_SESSION['innlogget'], 'bloggernavn' => $_SESSION['name']));

        } else {

//Skriv ut alle sidene til bloggeren, hvis du klikket på bloggemnet i listen på hovedsiden
            if (!empty($_GET['idblog']) && ctype_digit($_GET['idblog'])) {

                $blog = $blogdb->showpostsofblogger($_GET['idblog']);

                if (empty($blog)) {
                    $blogisempty = 1; //Hjelpevariabel
                } else {
                    $blogisempty = 0; //Hjelpevariabel
                }

                if ($_SESSION['id_blog'] != $_GET['idblog']) {
                    $notownerofthisbliog = true; //Hjelpevariabel
                } else {
                    $notownerofthisbliog = false; //Hjelpevariabel
                }

                echo $twig->render('allusersposts.twig', array('blogname' => $_SESSION['blogname'], 'blogisempty' => $blogisempty, 'notownerofthisbliog' => $notownerofthisbliog, 'arkhiv' => $arkhiv, 'lastfivecomments' => $lastfivecomments, 'topfiveposts' => $topfiveposts, 'blogowner' => $_SESSION['blogowner'], 'innloget' => $_SESSION['innlogget'], 'bloggernavn' => $_SESSION['name'], 'tempposts' => $blog));

            } else {

                // Vi viser en artikkel
                if (!empty($_GET['idpost']) && ctype_digit($_GET['idpost'])) {

                    $postpost = $blogdb->showPost($_GET['idpost']);
                    $postscomments = $blogdb->PostsComments($_GET['idpost']);
                    $views = $blogdb->setViews($_GET['idpost']);

                    echo $twig->render('allusersposts.twig', array('blogname' => $_SESSION['blogname'], 'postscomments' => $postscomments, 'arkhiv' => $arkhiv, 'lastfivecomments' => $lastfivecomments, 'topfiveposts' => $topfiveposts, 'post1' => $postpost, 'innloget' => $_SESSION['innlogget'], 'bloggernavn' => $_SESSION['name'], 'blogowner' => $_SESSION['blogowner'], 'postownerid' => $_SESSION['id_blog'], 'id_user' => $_SESSION['id_user']));

                } else {

                    //Artikler sortert etter kategori
                    if (!empty($_GET['idcat']) && ctype_digit($_GET['idcat'])) {

                        $catpost = $blogdb->showPostsInCategory($_GET['idcat']);

                        echo $twig->render('Category.twig', array('blogname' => $_SESSION['blogname'], 'catpost' => $catpost, 'arkhiv' => $arkhiv, 'lastfivecomments' => $lastfivecomments, 'topfiveposts' => $topfiveposts, 'blogowner' => $_SESSION['blogowner'], 'innloget' => $_SESSION['innlogget'], 'bloggernavn' => $_SESSION['name']));

                    } else {

                        //skriv ut hovedsiden, hvis vi ikke trykker på andre knapper
                        if (empty($_GET['page'])) {

                            $tags = $blogdb->tags();

                            echo $twig->render('toparticles.twig', array('blogname' => $_SESSION['blogname'], 'tags' => $tags, 'publ' => $_SESSION['publisere'], 'arkhiv' => $arkhiv, 'loginplus' => $_SESSION['loginsuccess'], 'registered' => $_SESSION['registr'], 'lastfiveblogs' => $lastfiveblogs, 'lastfivecomments' => $lastfivecomments, 'topfiveposts' => $topfiveposts, 'blogowner' => $_SESSION['blogowner'], 'allposts' => $allposts, 'innloget' => $_SESSION['innlogget'], 'bloggernavn' => $_SESSION['name']));

                            $_SESSION['registr'] = 0;
                            $_SESSION['loginsuccess'] = 0;

                        } else {

                            // Vedlikehold av navigasjonsmenyen
                            switch ($_GET['page']) {

                                case"about":
                                    if ($_SESSION['innlogget'] and $_SESSION['blogowner']) {

                                        $showaboutblog = $blogdb->showaboutblog($_SESSION['id_blog']);

                                        echo $twig->render('aboutblogger.twig', array('blogname' => $_SESSION['blogname'], 'arkhiv' => $arkhiv, 'showaboutblog' => $showaboutblog, 'lastfiveblogs' => $lastfiveblogs, 'lastfivecomments' => $lastfivecomments, 'topfiveposts' => $topfiveposts, 'blogowner' => $_SESSION['blogowner'], 'innloget' => $_SESSION['innlogget'], 'bloggernavn' => $_SESSION['name']));

                                    } else {

                                        $blogcategory = $blogdb->blogCategories();
                                        $om = true; //Hjelpevariabel

                                        echo $twig->render('aboutblogger.twig', array('blogname' => $_SESSION['blogname'], 'om' => $om, 'blogcategory' => $blogcategory, 'arkhiv' => $arkhiv, 'lastfiveblogs' => $lastfiveblogs, 'lastfivecomments' => $lastfivecomments, 'topfiveposts' => $topfiveposts, 'blogowner' => $_SESSION['blogowner'], 'innloget' => $_SESSION['innlogget'], 'bloggernavn' => $_SESSION['name']));
                                    }

                                    break;

                                case "category":

                                    $allcategories = $blogdb->showCategories();

                                    echo $twig->render('Category.twig', array('blogname' => $_SESSION['blogname'], 'allcat' => $allcategories, 'arkhiv' => $arkhiv, 'lastfivecomments' => $lastfivecomments, 'topfiveposts' => $topfiveposts, 'blogowner' => $_SESSION['blogowner'], 'innloget' => $_SESSION['innlogget'], 'bloggernavn' => $_SESSION['name']));

                                    break;

                                case "home":

                                    header("Location:" . $_SERVER['PHP_SELF']);

                                    break;

                                case "add":

                                    if ($_SESSION['innlogget']) {

                                        $cat = $blogdb->categories();

                                        echo $twig->render('leggartikkel.twig', array('blogname' => $_SESSION['blogname'], 'category' => $cat, 'publ' => $_SESSION['publisere'], 'arkhiv' => $arkhiv, 'lastfivecomments' => $lastfivecomments, 'topfiveposts' => $topfiveposts, 'blogowner' => $_SESSION['blogowner'], 'innloget' => $_SESSION['innlogget'], 'bloggernavn' => $_SESSION['name']));

                                    } else

                                        header("Location:" . $_SERVER['PHP_SELF']);

                                    break;

                                case  "myposts":

                                    if (!empty($_SESSION['id_blog'])) {

                                        $temppost = $blogdb->showpostsofblogger($_SESSION['id_blog']);

                                        if (empty($temppost)) {
                                            $blogisempty = 1; //Hjelpevariabel
                                        } else {
                                            $blogisempty = 0; //Hjelpevariabel
                                        }

                                        echo $twig->render('allusersposts.twig', array('blogname' => $_SESSION['blogname'], 'blogisempty' => $blogisempty, 'arkhiv' => $arkhiv, 'lastfivecomments' => $lastfivecomments, 'topfiveposts' => $topfiveposts, 'blogowner' => $_SESSION['blogowner'], 'innloget' => $_SESSION['innlogget'], 'bloggernavn' => $_SESSION['name'], 'tempposts' => $temppost));

                                    } else

                                        header("Location:" . $_SERVER['PHP_SELF']);

                                    break;

                                case  "profile":

                                    if ($_SESSION['innlogget']) {

                                        if ($_SESSION['blogowner']) {

                                            $showaboutblog = $blogdb->showaboutblog($_SESSION['id_blog']);

                                            echo $twig->render('aboutblogger.twig', array('blogname' => $_SESSION['blogname'], 'arkhiv' => $arkhiv, 'showaboutblog' => $showaboutblog, 'lastfiveblogs' => $lastfiveblogs, 'lastfivecomments' => $lastfivecomments, 'topfiveposts' => $topfiveposts, 'blogowner' => $_SESSION['blogowner'], 'innloget' => $_SESSION['innlogget'], 'bloggernavn' => $_SESSION['name']));

                                        } else {

                                            $blogcategory = $blogdb->blogCategories();

                                            $om = true; //Hjelpevariabel

                                            echo $twig->render('aboutblogger.twig', array('om' => $om, 'blogcategory' => $blogcategory, 'arkhiv' => $arkhiv, 'lastfiveblogs' => $lastfiveblogs, 'lastfivecomments' => $lastfivecomments, 'topfiveposts' => $topfiveposts, 'blogowner' => $_SESSION['blogowner'], 'innloget' => $_SESSION['innlogget'], 'bloggernavn' => $_SESSION['name']));
                                        }
                                    } else {

                                        header("Location:" . $_SERVER['PHP_SELF']);

                                    }
                                    break;

                                default:

                                    header("Location:" . $_SERVER['PHP_SELF']);

                                    break;

                            }
                        }
                    }
                }

            }
        }
    }
}

echo $twig->render('footer.twig', array());
