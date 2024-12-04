<?php
require_once __DIR__ . "/../Database.php";

class MFindNextResp
{

    public function getNextResp(DatabaseMain $db)
    {
        $sql =
            'SELECT ' .
            'u.id, ' .
            'u.prenom, ' .
            'u.nom, ' .
            'u.mail, ' .
            'u.login, ' .
            'u.croissant_buy ' .
            'FROM users AS u ' .
            'INNER JOIN participant p ON u.id = p.id_user ' .
            'ORDER BY u.croissant_buy LIMIT 1;';
        return $db->query($sql);
    }

    public function getNextRespEvent(DatabaseMain $db, $event_id)
    {
        $sql =
            'SELECT ' .
            'u.id, ' .
            'u.prenom, ' .
            'u.nom, ' .
            'u.mail, ' .
            'u.login, ' .
            'u.croissant_buy ' .
            'FROM users AS u ' .
            'INNER JOIN participant p ON u.id = p.id_user ' .
            'WHERE p.is_present = \'1\' ' .
            'AND p.id_event = \'' . $event_id . '\'' .
            'ORDER BY u.croissant_buy LIMIT 1;';
        return $db->query($sql);
    }

    /**
     * set is_responsable status for the specified user and event
     * @param DatabaseMain $db
     * @param String $event_id
     * @param $user_id
     * @param $status_resp
     * @return \DatabaseResult|false
     * @throws \DataBaseNotConnected
     */
    public function setRespEvent(\DatabaseMain $db, string $event_id, $user_id, $status_resp)
    {
        $query = "UPDATE participant SET " .
            "is_responsable = '" . $status_resp . "'" .
            " WHERE id_event = " . $event_id .
            " AND id_user = " . $user_id;

        return $db->query($query);
    }
}