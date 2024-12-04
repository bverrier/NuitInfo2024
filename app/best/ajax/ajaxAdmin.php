<?php
require_once __DIR__ . '/../inc/service/SSingup.php';
require_once __DIR__ . '/../inc/Session.php';
require_once __DIR__ . '/../inc/Database.php';
require_once __DIR__ . '/../inc/controller/CAdmin.php';
require_once __DIR__ . '/../inc/service/SAdmin.php';

$session = Session::getInstance();
if (!$session->sessionIsValid()){
    http_response_code(403);
    die("access denied");
}

$db = new Database();
$service = new SSingup($session);
$controller = new CAdmin($session);
$serviceAdmin = new SAdmin($session);


switch ($_POST['admin']){
    case 'addForm':
    case 'addModifyForm':
        echo $service->signupUser($db, $_POST);
        break;
    case 'getFormModif':
        $infoUser = $serviceAdmin->getInfoUser($db, $_POST['id']);
        echo $controller->getForm($db, $infoUser);
        break;
    case 'getFormulaire':
        echo $controller->getForm($db, null);
        break;
    default:
        break;
}