<?php

//Sett inn innledende sesjonsvariablene til brukeren er logget inn.
if (empty($_SESSION)) {
    $_SESSION['innlogget'] = false;
    $_SESSION['blogowner'] = false;
    $_SESSION['name'] = "Основной блог";
    $_SESSION['registr'] = 0;
    $_SESSION['loginsuccess'] = 0;
    $_SESSION['id_blog'] = NULL;
    $_SESSION['publisere'] = 0;
    $_SESSION['blogname'] = "Vår blog";
    $_SESSION['id_user'] = NULL;
}

//Ny brukerregistrering
if (isset($_POST['registrer'])) {

    $user = new User();
    $username = trim(filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS));
    $email = trim(filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL));

    if (!$blogdb->userExists($username)) {
        $user->setUsername($username);
        $_SESSION['registr'] = 1;

        if ((trim($_POST['password']) == trim($_POST['gjentapas']))) {
            $user->setPassword(password_hash(trim(filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS)), PASSWORD_DEFAULT));
            $_SESSION['registr'] = 1;

            if (!$blogdb->emailExists($email)) {
                $user->setEmail($email);
                $_SESSION['registr'] = 1;

// create a new cURL resource
                $ch = curl_init();
                $url = "http://localhost/ProsjektoppgaveGruppe7/index.php?id=";
                $epost = $user->getEmail();
// set URL and other appropriate options
                $id = md5(uniqid(rand(), 1));
                curl_setopt($ch, CURLOPT_URL, "http://kark.uit.no/internett/php/mailer/mailer.php?address=$epost&url=$url" . $id);

//curl_setopt($ch, CURLOPT_URL, "http://www.dagbladet.no/");
                curl_setopt($ch, CURLOPT_HEADER, 0);

                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// grab URL and pass it to the browser
                $output = curl_exec($ch);


// close cURL resource, and free up system resources
                curl_close($ch);
                $user->setIdVer($id);
                $user->setAccept(0);
                $test1 = $blogdb->registrerNyBruker($user);

            } else {
                $_SESSION['registr'] = 4;
            }
        } else {
            $_SESSION['registr'] = 2;
        }


    } else {
        $_SESSION['registr'] = 3;
    }

} else {
    $registered = 0;
}

//Bekreftelse av registrering
if (!empty($_GET['id'])) {

    $blogdb->acceptere(filter_input(INPUT_GET, "id", FILTER_SANITIZE_SPECIAL_CHARS));
    $blogdb->newAccept(filter_input(INPUT_GET, "id", FILTER_SANITIZE_SPECIAL_CHARS));
    $_SESSION['loginsuccess'] = 4;
}

if (isset($_POST['login'])) {
    $username = trim(filter_input(INPUT_POST, "InputLogin", FILTER_SANITIZE_SPECIAL_CHARS));
    $password = trim(filter_input(INPUT_POST, "InputPassword", FILTER_SANITIZE_SPECIAL_CHARS));

    if ($blogdb->userExists($username)) {
        $blogowner = $blogdb->Blogowner($username);
        $test = $blogdb->login($username, $password, $blogowner);

    } else {
        $_SESSION['loginsuccess'] = 2;

    }

}

if (!empty($_GET['page']))
    if ($_GET['page'] == 'logout') {
        $blogdb->logout();
        header("Location:" . $_SERVER['PHP_SELF']);
    }