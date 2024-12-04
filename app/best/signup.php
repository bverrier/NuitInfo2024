<?php
//import lib
require_once __DIR__ . '/lib/croissantLib.php';
require_once __DIR__ . '/inc/Database.php';
require_once __DIR__ . '/conf/config.php';
require_once __DIR__ . '/lib/Header.php';
require_once __DIR__ . '/inc/Session.php';
require_once __DIR__ . '/inc/service/SSingup.php';
require_once __DIR__ . '/inc/controller/CSignup.php';

// Gestion de l’inscription de l’utilisateur
$session = Session::getInstance();
$service = new SSingup($session);
$db = new Database();

// Inscription de l’utilisateur
if(!empty($_POST) && !empty($_POST['signup'])) {
   $msg = $service->signupUser($db, $_POST);
   echo '<div class="container text-center mt-4">'.$msg.'</div>'."\n";
}

// Page du formulaire d'inscription de l’utilisateur
$controller  = new CSignup();
echo $controller->display();

