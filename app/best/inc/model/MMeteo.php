<?php

namespace model;

use DatabaseMain;

require_once __DIR__ . '/../Database.php';

/**
 * Class MAccueil
 *
 * This class handles event-related data retrieval from the database.
 */
class MMeteo
{
    /**
     * Retrieves all events from the database.
     *
     * @param \DatabaseMain $db The database connection object.
     * @return \DatabaseResult|false
     * @throws \DataBaseNotConnected
     */
    public function getAllEvents(\DatabaseMain $db) {
        $query = "SELECT ".
            "e.id AS event_id,".
            "e.date_event,".
            "e.nb_croissant_total,".
            "e.is_vacance ".
        "FROM event AS e;";
        return $db->query($query);
    }

    /**
     * Retrieve an event by id
     * @param DatabaseMain $db
     * @param String $event_id The id of the event to retrieve
     * @return \DatabaseResult|false
     * @throws \DataBaseNotConnected
     */
    public function getEventById(DatabaseMain $db, String $event_id) {
        $query = "SELECT ".
            "e.id AS event_id,".
            "e.date_event," .
            "e.nb_croissant_total," .
            "e.is_vacance " .
            "FROM event AS e " .
            "WHERE e.id = " . $event_id;

        return $db->query($query);
    }

    /**
     * Retrieves the participants of a specific event from the database.
     *
     * @param \DatabaseMain $db The database connection object.
     * @param int $event_id The ID of the event.
     * @return \DatabaseResult|false
     * @throws \DataBaseNotConnected
     */
    public function getParticipantsEvent(\DatabaseMain $db, $event_id) {
        $query = "SELECT ".
            "p.id_user,".
            "u.prenom,".
            "u.nom,".
            "p.is_responsable,".
            "p.nb_croissant,".
            "p.is_present ".
        "FROM users AS u ".
        "INNER JOIN participant AS p ON p.id_user = u.id ".
        "WHERE p.id_event = ".$event_id;

        return $db->query($query);
    }

    /**
     * Returns is_responsable status for the specified participant and event
     * @param DatabaseMain $db
     * @param $event_id
     * @param $user_id
     * @return \DatabaseResult|false
     * @throws \DataBaseNotConnected
     */
    public function getParticipantIsResponsableEvent(\DatabaseMain $db, $event_id, $user_id) {
        $query = "SELECT ".
            "p.is_responsable".
            " FROM participant AS p ".
            "WHERE p.id_event = ".$event_id." AND p.id_user = ".$user_id;

        return $db->query($query);
    }

    /**
     * get the total number of croissants wanted by the participants of the specified event
     * @param DatabaseMain $db
     * @param $event_id
     * @return \DatabaseResult|false
     * @throws \DataBaseNotConnected
     */
    public function getSumCroissantsParticipantsEvent(\DatabaseMain $db, $event_id) {
        $query = "SELECT ".
            "SUM(p.nb_croissant) AS nb_croissant ".
            "FROM participant AS p ".
            "WHERE p.id_event = '".$event_id."'".
            " AND p.is_present = '1'";

        return $db->query($query);
    }
}