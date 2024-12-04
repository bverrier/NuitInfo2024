<?php


use DatabaseMain;

require_once __DIR__ . '/../Database.php';

/**
 * Class MAccueil
 *
 * This class handles event-related data retrieval from the database.
 */
class MRetro
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

}