<?php

require_once __DIR__ . '/inc/service/SScheduler.php';
require_once __DIR__ . '/lib/croissantLib.php';
require_once __DIR__ . '/inc/controller/CScheduler.php';
require_once __DIR__ . '/inc/Database.php';
require_once __DIR__ . '/conf/config.php';
require_once __DIR__ . '/inc/service/SCalendar.php';
require_once __DIR__ . '/inc/service/SHolidays.php';

/**
 * @throws DataBaseNotConnected
 * @throws Exception
 */
function IsEventGenerated(String $date): bool{
    $db = new Database();

    $db->query('START TRANSACTION;');

    $res = $db->query('SELECT event.date_event FROM event ORDER BY event.date_event LIMIT 1;');
    if (!$res instanceof DatabaseResult) {
        $db->query('ROLLBACK;');
        throw new Exception("Error during event selection for generation check.");
    }
    $db->query('COMMIT;');
    $events = $res->toList();
    return !empty($events);
}

/**
 * @throws DateMalformedStringException
 * @throws Exception
 */
function generateWednesdays($year): void
{
    if (IsEventGenerated($year)) {
        return;
    }
    $wednesdays = [];
    // Set the first day of the year
    $date = new DateTime("first wednesday of January $year");

    // Loop through all the Wednesdays of the year
    while ($date->format('Y') == $year) {
        // Add the Wednesday to the array in the required format
        $wednesdays[] = $date->format('Y-m-d 00:00:00');
        // Move to the next Wednesday
        $date->modify('next wednesday');
    }
    // Check if event and resp are valid
    if (empty($wednesdays)){
        throw new Exception("An error occured at the generation of events.");
    }
    $db = new Database();
    $db->query('START TRANSACTION;');

    foreach ($wednesdays as $wednesday) {

        $values = array(
            'date_event' => '\''.$wednesday.'\'',
            'nb_croissant_total' => '\'0\'',
            'is_vacance' => '\'0\''
        );

        $res = $db->insert('event', $values);


        // Check if result is valid
        if (!$res instanceof DatabaseResult) {
            $db->query('ROLLBACK;');
            throw new Exception("Error during event insertion.");
        }
    }

    $db->query('COMMIT;');
}


/**
 * @throws DateMalformedStringException
 * @throws Exception
 */
function handleFunctions(): void
{
    $today = new DateTime();
    $dow = $today->format('w');
    $year = $today->format('Y');
    $month = $today->format('m');
    $day = $today->format('d');


    switch ($dow) {
        case 5:
            scheduleWednesday();
            break;
        case 3:
            addWednesdayInGCalendar();
            sendRespMail(false);
            break;
		case 1:
			sendRespMail(true);
			break;
    }

    if ($month == '1' && $day == '1') {
        generateWednesdays($year);
    }


}


/**
 * @throws Exception
 */
function sendRespMail(bool $isRappel):void
{
    $db = new Database();
    $service = new SScheduler();

    // Start from today's date
    $currentDate = new DateTime();
    $currentDate = $currentDate->format("Y-m-d");

    $resp = $service->getEventResp($db,$currentDate);
    if (!isset($resp[0])){
        throw new Exception("Couldn't get event resp.");
    }
	//sending the message
	$msg ='';
	if ($isRappel) {
		$msg = 'Bonjour '.htmlsecure($resp[0]['prenom']) . ' '. htmlsecure($resp[0]['nom']).' Pour rappel vous êtes le responsable du prochain mercredi';
	} else {
		$msg = 'Bonjour '.htmlsecure($resp[0]['prenom']) . ' '. htmlsecure($resp[0]['nom']).' Vous êtes le nouveau responsable du prochain mercredi';
	}
	sendEmail($db, ADMINLOGIN,$resp[0]['id'], $msg);

}

/**
 * @throws Exception
 */
function scheduleWednesday(): void
{
    $db = new Database();
    $service = new SScheduler();

    // Start from today's date
    $currentDate = new DateTime();
    $currentDate = $currentDate->format("Y-m-d");

    $event = $service->getNextEvent($db, $currentDate);

    $resp = $service->getEventResp($db,$currentDate);

    // Check if event and resp are valid
    if (empty($event) || empty($resp)) {
        throw new Exception("No valid event or responsible found.");
    }

    $where = array(
        'id_user = \''.$db->escape($resp[0]['id']).'\' ',
        ' id_event = \''.$db->escape($event[0]['id']).'\''
    );
    $values = array('is_responsable' => '\'1\'');

    $res = $db->update('participant', $where, $values);

    // Check if result is valid
    if (!$res instanceof DatabaseResult) {
        throw new Exception("Error when picking responsible.");
    }
}

/**
 * @throws DateMalformedStringException
 * @throws Exception
 */
function addWednesdayInGCalendar() {
    $db = new Database();
    $sholidays = new SHolidays(null, false);
    $scalendar = new SCalendar();
    $currentDate = new DateTime();
    $nextWednesday = $currentDate->modify('next Wednesday');
    $nextWednesdayFormatted = $nextWednesday->format(DateTimeInterface::RFC3339);
    $allHolidays = $sholidays->getHolidayDatesInRFCFormat($db, true);
    var_dump($allHolidays);
    $scalendar->createEventEveryWeekInScheduler($nextWednesdayFormatted, $allHolidays);
}

//handleFunctions();
//generateWednesdays("2024");
//scheduleWednesday();
//die;
$now = new DateTime();

try {
    handleFunctions();
} catch (Exception $e) {
    //var_dump($e);
}
//var_dump("Ran scheduler at ".$now->format('Y-m-d H:i:s'));

