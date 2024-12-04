<?php
require_once __DIR__ . "/../Database.php";
require_once __DIR__ . "/../model/MScheduler.php";
require_once __DIR__ . "/../../lib/croissantLib.php";
class SScheduler
{
    private MScheduler $model;

    public function __construct()
    {
        $this->model = new MScheduler();
    }


    /**
     * @param DatabaseMain $db Database
     * @param String $date of the event to check
     * @return array of the responsible user for the date
     * @throws Exception
     */
    public function getEventResp(DatabaseMain $db, String $date):array
    {
        // Execute query to get all users
        if (empty($date))
        {
            throw new Exception("Date invalide");
        }

        if (!isValidDate($date))
        {
            throw new Exception("Date invalide");
        }

        $res = $this->model->getEventResp($db,$date);

        // Check if result is valid
        if (!$res instanceof DatabaseResult) {
            throw new Exception("Erreur lors de la récupération des utilisateurs.");
        }

        // Return the result as an array
        return $res->toList();
    }

    /**
     * @param DatabaseMain $db Database
     * @param String $date date to check
     * @return array of vacances that are on the date
     * @throws Exception
     */
    public function getVacances(DatabaseMain $db, String $date):array
    {
        $res =  $this->model->getVacances($db, $date);
        if (!$res instanceof DatabaseResult) {
            throw new Exception("Erreur lors de la récupération des vacances.");
        }
        return $res->toList();
    }

    /**
     * @param DatabaseMain $db database
     * @param String $date refereence date
     * @return array the next event  scheduled
     * @throws Exception
     */
    public function getNextEvent(DatabaseMain $db,String $date):array
    {
        $res =  $this->model->getNextEvent($db,$date);
        if (!$res instanceof DatabaseResult) {
            throw new Exception("Erreur lors de la récupération du prochain event.");
        }
        return $res->toList();
    }
}