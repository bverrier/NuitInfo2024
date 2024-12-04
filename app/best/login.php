<?php
//import lib
require_once __DIR__ . '/lib/croissantLib.php';
require_once __DIR__ . '/inc/Database.php';
require_once __DIR__ . '/conf/config.php';
require_once __DIR__ . '/inc/Session.php';
require_once __DIR__ . '/inc/controller/CLogin.php';
require_once __DIR__ . '/inc/service/SLogin.php';

$session = Session::getInstance();
$service = new SLogin();

// Déconnexion de l’utilisateur
if (!empty($_POST['logout']) && $_POST['logout'] == LOGOUT) {
    $session->logout();
}

// Connection de l’utilisateur
try {
    $res = false;
    if (isset($_POST['connection'])) {
        $res = $service->connectUser($_POST);
    }
    // Si le login + mdp est correct, on fait une redirection version le compte de l’utilisateur
    if ($res) {
        // Redirection vers index.php
		$_GET['page'] == 'Accueil';
        header('Location:index.php?page=Accueil');
    }
} catch (Exception $e) {
    echo
        '<div class="container mt-4 text-center">' . "\n" .
            getAlert('danger',array($e->getMessage())) .
        '</div>' . "\n";
}

// Afficher le formulaire de login
$controller = new CLogin();
echo $controller->display();
