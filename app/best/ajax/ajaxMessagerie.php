<?php
require_once __DIR__ . '/../inc/service/SMessagerie.php';
require_once __DIR__ . '/../inc/controller/CMessagerie.php';
require_once __DIR__ . '/../inc/Session.php';
require_once __DIR__ . '/../inc/Database.php';

$session = Session::getInstance();
if (!$session->sessionIsValid()){
	http_response_code(403);
	die("access denied");
}

$db = new Database();
$service = new SMessagerie($session);
$controlleur = new CMessagerie($session);


switch ($_POST['messagerie']){
	case 'addMail':
		echo $service->checkForm($db, $_POST);
		break;
	case 'getForm':
		echo $controlleur->getForm($db, null);
		break;
	default:
		break;
}