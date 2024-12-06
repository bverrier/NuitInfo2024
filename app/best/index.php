<?php
//import lib
require_once __DIR__ . '/lib/croissantLib.php';
require_once __DIR__ . '/lib/Header.php';
require_once __DIR__ . '/inc/Database.php';
require_once __DIR__ . '/conf/config.php';
require_once __DIR__ . '/inc/Session.php';
require_once __DIR__ . '/inc/controller/CRetro.php';

$session = Session::getInstance();

// Déconnexion de l’utilisateur
if (!empty($_POST['logout']) && $_POST['logout'] == LOGOUT) {
    $session->logout();
}

$controller = new CRetro($session);
$db = new Database();

$header = new Header();
$header->setFileCss("css/retro.css");
//si il faut un script js appler la fonction setFileJS
$header->setFileJS("js/ajax/retro.js");
echo $header->getHeader("Retro");
echo $controller->display($db);
echo $header->getEnd();

