<?php

class Mholidays
{
    public function getAllHolidays(DatabaseMain $db){
        $sql =
            'SELECT '.
				'id, '.
				'debut, '.
				'fin, '.
				'annee, '.
				'Nom '.
            'FROM vacances WHERE is_activ=1';
        return $db->query($sql);
    }
    public function getHolidayInfo(DatabaseMain $db,string $id)
    {
        $sql =
            'SELECT '.
				'id, '.
				'debut, '.
				'fin, '.
				'annee, '.
				'Nom,'.
				'is_activ '.
            'FROM vacances '.
        'WHERE  id='. htmlsecure($id);
        return $db->query($sql);
    }

    public function updateEventStatusToHoliday(DatabaseMain $db, bool $is_delete = false, $debut = "", $fin = "") {
        if ($is_delete) {
            // If delete, set is_vacance to FALSE for events in the given date range
            $sql = "UPDATE event e
            SET e.is_vacance = FALSE
            WHERE e.date_event BETWEEN '$debut' AND '$fin'";

            return $db->query($sql);
        } else {
            // Update all rows, setting is_vacance based on whether there is an active holiday
            $sql = 'UPDATE event e
            SET e.is_vacance = (
                SELECT CASE 
                    WHEN EXISTS (
                        SELECT 1
                        FROM vacances v
                        WHERE e.date_event BETWEEN v.debut AND v.fin
                        AND v.is_activ = 1
                    ) THEN TRUE
                    ELSE FALSE
                END
            )';

            return $db->query($sql);
        }
    }


}