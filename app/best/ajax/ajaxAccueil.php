<?php
require_once __DIR__ . '/../inc/Session.php';
require_once __DIR__ . '/../inc/Database.php';
require_once __DIR__ . '/../inc/service/SAccueil.php';
require_once __DIR__ . '/../inc/controller/CAccueil.php';

$session = Session::getInstance();
if (!$session->sessionIsValid()){
    http_response_code(403);
    die("access denied");
}

$db = new Database();
$service = new SAccueil();
$controller = new CAccueil($session, $service);


switch ($_POST['accueil']){
    case 'detailsEvent':
        try {
            echo json_encode($service->getEvent($db, $_POST['detailsEventId']));
        } catch (DataBaseNotConnected $e) {
            echo $e->getMessage();
        }
        break;
    case 'formValiderPresenceEvent':
        echo json_encode($service->updateParticipantInfo($db, $_POST, $session->getIdUser()));
        break;
    default:
        break;
}