<?php
require_once __DIR__ . "/../Database.php";

class MScheduler
{
    /**
     * @param DatabaseMain $db
     * @param String $date
     * @return DatabaseResult|false
     * @throws DataBaseNotConnected
     */
    public function getVacances(DatabaseMain $db, String $date)
    {
        $sql =
            "SELECT ".
                "vacances.debut, ".
                "vacances.fin ".
            "FROM vacances ".
            "WHERE vacances.debut <= '". $db->escape($date) ."'".
            "AND vacances.fin >= ". $db->escape($date) . "'";
        return $db->query($sql);
    }
    public function getEventResp(DatabaseMain $db, String $date)
    {
        $sql =
            'SELECT '.
				'u.id, '.
				'u.nom, '.
				'u.prenom, '.
				'u.mail, '.
				'u.croissant_buy, '.
            	'p.is_responsable '.
            'FROM users AS u '.
            'INNER JOIN participant p ON u.id = p.id_user '.
            'INNER JOIN event ON p.id_event = event.id '.
            'WHERE event.date_event >= \''.$db->escape($date).'\' '.
            'ORDER BY '.
            'CASE WHEN p.is_responsable = 1 THEN 0 ELSE 1 END, '.
            'u.croissant_buy ASC '.
            'LIMIT 1;';
        return $db->query($sql);
    }

    /**
     * @param DatabaseMain $db
     * @param String $date
     * @return DatabaseResult|false
     * @throws DataBaseNotConnected
     */
    public function getNextEvent(DatabaseMain $db,String $date)
    {
        $sql =
            "SELECT ".
            "event.id ,".
            "event.date_event, ".
            "event.nb_croissant_total ".
            "FROM event ".
            "WHERE event.is_vacance = 0 ".
            "AND event.date_event >= '".$db->escape($date)."' ".
            "ORDER BY event.date_event ".
            "LIMIT 1";

        return $db->query($sql);
    }
}