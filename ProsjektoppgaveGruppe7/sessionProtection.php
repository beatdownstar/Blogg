<?php
//sesjon tyveribeskyttelse

if ($_SESSION['innlogget'] == true) {

    //vi sjekker tilfeldigheten av ip-adresse og nettleser
    $isBrowserMatch = ($_SESSION['browser'] === $_SERVER['HTTP_USER_AGENT']);
    $isIpMatch = ($_SESSION['ip'] === $_SERVER['REMOTE_ADDR']);

    if (!$isIpMatch || !$isBrowserMatch) {

        $blogdb->logout();
    }
}