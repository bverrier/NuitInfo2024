<?php

use model\MAccueil;

require_once __DIR__ . '/../model/MAccueil.php';
require_once __DIR__ . '/../../lib/ErrorForm.php';
require_once __DIR__ . '/../Database.php';
require_once __DIR__ . '/SFindNextResp.php';

/**
 * The SAccueil class provides functionalities to retrieve events and participants information
 * from the database using the MAccueil model.
 */
class SAccueil
{
    private MAccueil $model;
    private ErrorForm $errorForm;

    private SFindNextResp $findNextResp;

    public function __construct(?MAccueil $model = null)
    {
		$this->errorForm = new ErrorForm();
        $this->model = $model ?? new MAccueil();
        $this->findNextResp = new SFindNextResp();
    }

    /**
     * Retrieves a list of events from the database, including participants for each event.
     *
     * @param DatabaseMain $db The database connection object.
     * @return array The list of events with their participants.
     * @throws Exception If there is an error retrieving the events from the database.
     */
    public function getEvents(DatabaseMain $db): array
    {
        $res = $this->model->getAllEvents($db);
        if (!$res instanceof DatabaseResult) {
            throw new Exception("Erreur de la base de donnée pour la liste des mercredi");
        }
        $res = $res->toList();

        // add participants for each event
        foreach ($res as &$event) {
                $participants = $this->getParticipantsEvent($db, $event['event_id']);
                $event['participants'] = $participants;
        }

        return $res;
    }

    /**
     * Retrieve a single event from the database
     * @param DatabaseMain $db
     * @param int $event_id the id of the event to get
     * @return array
     * @throws DataBaseNotConnected
     */
    public function getEvent(DatabaseMain $db, int $event_id): array
    {
        $res = $this->model->getEventById($db, $event_id);
        if (!$res instanceof DatabaseResult) {
            throw new Exception("Erreur de la base de donnée pour récupérer l'event " . $event_id);
        }
        $res = $res->toList();
        if (sizeof($res) == 0) return [];
        $res = $res[0];

        try {
            $participants = $this->getParticipantsEvent($db, $res['event_id']);
        } catch (Exception $e) {

        }

        $res['participants'] = $participants;
        return $res;
    }

    /**
     * Retrieves the list of participants for a given event from the database.
     *
     * @param DatabaseMain $db The database connection object.
     * @param mixed $event_id The ID of the event for which participants are to be fetched.
     * @return array The list of participants for the specified event.
     * @throws Exception If there is an error retrieving the participants from the database.
     */
    public function getParticipantsEvent(DatabaseMain $db, $event_id): array
    {
        // check event_id
        if (!$event_id || !is_numeric($event_id)) {
            throw new InvalidArgumentException("Erreur: Identifiant de l'événement invalide");
        }
        
        $res = $this->model->getParticipantsEvent($db, $event_id);
        if (!$res instanceof DatabaseResult) {
            throw new Exception("Erreur de la base de donnée pour la liste des participants de l'événement");
        }
        return $res->toList();
    }

	/**
	 * @param DatabaseMain $db
	 * @param array $data
	 * @param int $idUser
	 * @return array
	 */
    public function updateParticipantInfo(DatabaseMain $db, array $data, int $idUser): array {

		if (empty($data['id_event']) || !ctype_digit($data['id_event'])) {
			$this->errorForm->addError('Mauvais évènement');
		}

        if (!ctype_digit($data['nb_croissant']) || (int) $data['nb_croissant'] < 0) {
            $this->errorForm->addError('Nombre de croissants invalide');
        }

		if ($data['present'] == '' || !ctype_digit($data['present'])) {
			$this->errorForm->addError('Mauvaise présence');
		}

		if (count($this->errorForm->getArray()) == 0) {
			$val = array(
                "nb_croissant" => '\''.$data['nb_croissant'].'\'',
				"is_present" => '\''.$db->escape($data['present']).'\'',
			);

			$wheres = array(
				'id_user = \'' . $db->escape($idUser) . '\' ',
				' id_event = \'' . $db->escape($data['id_event']) . '\' ',
			);

			$res = $db->update('participant', $wheres, $val);

			if (!$res instanceof DatabaseResult) {
				return [
                    "status" => "error",
                    "message" => getAlert('danger', array('Erreur de la base de données'))
                ];
			}

            $infoUser = $this->model->getParticipantIsResponsableEvent($db, $data['id_event'], $idUser);

            if (!$infoUser instanceof DatabaseResult) {
                return [
                    "status" => "error",
                    "message" => getAlert('danger', array('Erreur de la base de données'))
                ];
            }
            $infoUser = $infoUser->toList()[0];

            if ($data['present'] == '0' && $infoUser['is_responsable'] == '1') {
                try {
                    $this->findNextResp->changeRespEvent($db, $data['id_event'], $idUser);
                } catch (Exception $e) {
                    return [
                        "status" => "error",
                        "message" => getAlert('danger', array('Erreur lors du changement de responsable'))
                    ];
                }
            }


            $res = $this->model->getSumCroissantsParticipantsEvent($db, $data['id_event']);
            if (!$res instanceof DatabaseResult) {
                return [
                    "status" => "error",
                    "message" => getAlert('danger', array('Erreur lors de la récupération du nombre total de croissants'))
                ];
            }

            $val = array(
                "nb_croissant_total" => '\''.$res->toList()[0]['nb_croissant'].'\''
            );

            $wheres = array(
                ' id = \'' . $db->escape($data['id_event']) . '\' ',
            );

            $db->update('event', $wheres, $val);

			return [
                "status" => "success",
                "nb_croissant" => $data['nb_croissant'],
                "is_present" => $data['present'],
                "message" => getAlert('success', array('Présence à l\'événement modifiée!'))
            ];
		}

		return [
            "status" => "error",
            "message" => getAlert('danger', $this->errorForm->getArray())
        ];
    }
}