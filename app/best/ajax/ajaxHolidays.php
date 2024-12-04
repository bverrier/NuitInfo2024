<?php


use controller\CHolidays;

require_once __DIR__ . '/../inc/service/SSingup.php';
require_once __DIR__ . '/../inc/Session.php';
require_once __DIR__ . '/../inc/Database.php';
require_once __DIR__ . '/../inc/controller/CHolidays.php';
require_once __DIR__ . '/../inc/service/SHolidays.php';

$session = Session::getInstance();
if (!$session->sessionIsValid()){
	http_response_code(403);
	die("access denied");
}
$input = file_get_contents("php://input");
$data = json_decode($input, true);
// Parse the data as FormData
parse_str($input, $data);
$db = new Database();
$service = new SSingup($session);
$controller = new CHolidays($session);
$serviceHoliday = new SHolidays($session);
// Parse the data as FormData
parse_str($input, $data);

// Extract operation type
$operationType = isset($_POST['operationType']) ? $_POST['operationType']: null;
switch ($operationType){
	case 'addHoliday':
		$tmp = $serviceHoliday->addHolidays($db, json_decode($_POST['holidays']));
		echo $tmp;
		break;
	case 'getFormModif':
		$infoHoliday = $serviceHoliday->getHolidayInfo($db, $_POST['id']);

		echo $controller->getModifForm($db, $infoHoliday);
		break;
	case 'ModifHoliday':
		$wehres = array('id = '.$_POST['id']);
		$updatedHoliday = array(
			'id' => $_POST['id'],
			'Nom' => $_POST['Nom'],
			'debut' => $_POST['debut'],
			'fin' => $_POST['fin'] ,
		);

		$tmp = $serviceHoliday->updateHoliday($db, $wehres, $updatedHoliday);
		echo $tmp;

		break;
	case 'deleteHoliday':
		$wheres = array('id= '. $_POST['id']);

		echo $serviceHoliday->deleteHoliday($db, $wheres, $_POST['id']);
		break;
	case 'getDeleteModal':
		echo $controller->getDelete();
		break;
	case 'getFormulaire':
		echo $controller->getForm($db, null);
		break;
	default:
		break;
}