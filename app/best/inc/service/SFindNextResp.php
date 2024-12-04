<?php
require_once __DIR__ . "/../Database.php";
require_once __DIR__ . "/../model/MFindNextResp.php";


class SFindNextResp
{
    private MFindNextResp $model;
    public function __construct()
    {
        $this->model = new MFindNextResp();
    }
    /**
     * @return void
     *
     * @throws Exception
     */
    public function getNextResp(DatabaseMain $db):array
    {
        // Execute query to get all users

        $res = $this->model->getNextResp($db);

        // Check if result is valid
        if (!$res instanceof DatabaseResult) {
            throw new Exception("Erreur lors de la récupération des utilisateurs.");
        }

        // Return the result as an array
        return $res->toList();
    }

    public function changeRespEvent(DatabaseMain $db, string $event, string $old_resp) {
        $res =  $this->model->setRespEvent($db, $event, $old_resp, '0');

        if (!$res instanceof DatabaseResult) {
            throw new Exception("Erreur lors du retrait de l'ancien responsable");
        }

        $res = $this->model->getNextRespEvent($db, $event);

        if (!$res instanceof DatabaseResult) {
            throw new Exception("Erreur lors de la désignation du nouveau responsable");
        }
        $newResp = $res->toList()[0]['id'];

        $res =  $this->model->setRespEvent($db, $event, $newResp, '1');
        if (!$res instanceof DatabaseResult) {
            throw new Exception("Erreur lors de l'update du nouveau responsable");
        }
    }
}