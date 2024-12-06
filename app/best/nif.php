<?php

require_once __DIR__ . '/lib/croissantLib.php';
require_once __DIR__ . '/inc/Database.php';
require_once __DIR__ . '/inc/Session.php';
require_once __DIR__ . '/conf/config.php';

/*
 * Exemple de comment utiliser les sessions.
 */
$session = Session::getInstance();
if (!$session->sessionIsValid()) {
	http_response_code(403);
	die('Access denied');
}

$document = '';

// Si la variable $_GET['page'] est vide ou erroné
// Faire le test avant même de parcourir le switch
if (empty($_GET['page']) || !in_array($_GET['page'], ALL_PAGES)) {
	require_once __DIR__ . '/inc/Page.php';
	$page = new Page($session, 'Page Inexistante');
	$document = $page->getDocument("Erreur", '<p class="alert alert-danger">Aucune page n\'a été sélectionnée, car l\'url est incorrecte</p>', false);
	echo $document;
	die;
}
switch ($_GET['page']) {
	case 'Météo' :
		require_once __DIR__ . '/inc/controller/CMeteo.php';
		$controller = new CMeteo($session);
		break;
	case 'Admin' :
		require_once __DIR__ . '/inc/controller/CAdmin.php';
		$controller = new CAdmin($session);
		break;
	default:
		require_once __DIR__ . '/inc/Page.php';
		$controller = new Page($session, 'Page Inexistante');
		break;
}
$db = new Database();
try {
	if (!empty($_GET['id'])) {
		$document = $controller->getDocument($db, null, null, $_GET['id']);
	} else {
		$document = $controller->getDocument($db, null, null, null);
	}
} catch (Exception $e) {
	$document = $controller->getDocument($db, null, '<p class="alert alert-danger">' . $e->getMessage() . '</p>', null);
} finally {
	$db->close();
}

echo $document;


