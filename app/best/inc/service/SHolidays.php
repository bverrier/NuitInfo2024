<?php
require_once __DIR__ . '/../model/Mholidays.php';
require_once __DIR__ . '/../Session.php';
require_once __DIR__ . '/../../lib/ErrorForm.php';
require_once __DIR__ . '/../../lib/croissantLib.php';
class SHolidays
{
	private Mholidays $model;

	private Session $session;

	private ErrorForm $errorForm;

	public function __construct(Session $session) {

		$this->session = $session;
		$this->model = new Mholidays();
		$this->errorForm = new ErrorForm();
	}
	public function validateDate(DateTime $date, string $format = 'Y-m-d') :bool{
		return $date instanceof DateTime;
	}
	public function validateAndInsertHolidayDates(array $holidays, DatabaseMain $db): void
	{
		$dateDictionary = [];
		$duplicateIndexes = [];
		$startDate = null;
		$endDate = null;
		foreach ($holidays as $index => $holiday) {
			// Check for empty fields
			if (empty($holiday->Nom) || empty($holiday->debut) || empty($holiday->fin)) {
				$this->errorForm->addError('Veuillez remplir les champs manquants des vacances ' . ($index + 1) . '.');
			}
			if (!preg_match('/^[a-zA-ZÀ-ÿ\s]+$/', $holiday->Nom)) {
				$this->errorForm->addError('Le nom ne doit pas contenir de caractères spéciaux pour les vacances ' . ($index + 1) . '.');
			}
			if (strlen($holiday->debut) === 0) {
				$this->errorForm->addError('Le format de la date de début est invalide pour les vacances '  . ($index + 1) . '.');
			}
			else{
				$startDate = new DateTime($holiday->debut);
			}
			if (strlen($holiday->fin) === 0) {
				$this->errorForm->addError('Le format de la date de fin est invalide '. ($index + 1) . '.');
			}
			else{
				$endDate = new DateTime($holiday->fin);
			}


			if (($startDate != null && $endDate != null) && ($startDate == $endDate)) {
				$this->errorForm->addError('La date de début doit être différente de la date de fin pour les vacances ' . ($index + 1) . '.');
			} elseif ($startDate > $endDate) {
				$this->errorForm->addError('La date de début doit être inférieure à la date de fin pour les vacances ' . ($index + 1) . '.');
			}
			if($startDate != null && $endDate != null){
				$datePair = $startDate->format('Y-m-d') . '|' . $endDate->format('Y-m-d');

				if (array_key_exists($datePair, $dateDictionary)) {
					$duplicateIndexes[] = $dateDictionary[$datePair];
					$duplicateIndexes[] = ($index + 1);
				} else {
					$dateDictionary[$datePair] = ($index + 1);
				}
			}

		}

		if (!empty($duplicateIndexes)) {
			$duplicateIndexes = array_unique($duplicateIndexes);  // Ensure unique indexes
			$this->errorForm->addError("Les dates de début et de fin sont identiques entre les vacances " . implode(", ", $duplicateIndexes) . " de la liste.");
		}

		if (count($this->errorForm->getArray()) == 0) {
			foreach ($holidays as $holiday) {
				$values = array(
					'Nom' => '\'' . $db->escape($holiday->Nom) . '\'',
					'debut' => '\'' . $db->escape($holiday->debut) . '\'',
					'fin' => '\'' . $db->escape($holiday->fin) . '\'',
					'annee' => '\'' . date('Y') . '\'',
					'is_activ' => '\'' . 1 . '\'',
				);
				$db->insert('vacances', $values);
			}
		}
	}



	public function addHolidays(DatabaseMain $db, array $holidays): string
	{

        if (!($this->session->sessionIsValid() && $this->session->isAdmin())) {
            http_response_code(403);
            die('Session expirée');
        }
        $html = "";
        $db->query("START TRANSACTION;");
        $this->validateAndInsertHolidayDates($holidays,$db);
        $updateResult = $this->model->updateEventStatusToHoliday($db);
        $res = $this->errorForm->getArray();
        if(count($res) == 0 && $updateResult instanceof DatabaseResult){
            $db->query("COMMIT;");
            $html .= getAlert('success', array("Vacances ajoutées"));
        }
        else {
            $html .= getAlert('danger', $this->errorForm->getArray());
            $db->query("ROLLBACK;");
        }
        return $html;
    }

	public function getAllHolidays(DatabaseMain $db) : ?array
	{
		if ($this->session->sessionIsValid() && $this->session->isAdmin()) {
			$res = $this->model->getAllHolidays($db);
			if (!$res instanceof DatabaseResult) {
				throw new Exception("Erreur de la base de données");
			}
			return $res->toList();
		}
		return [];
	}
	public function getHolidayInfo(DatabaseMain $db, string $id)
	{
		if ($this->session->sessionIsValid() && $this->session->isAdmin()) {
			$res = $this->model->getHolidayInfo($db, $id);
			if (!$res instanceof DatabaseResult) {
				throw new Exception("Erreur de la base de données");
			}
			return $res->toList();
		}
		return [];
	}
	public function updateHoliday(DatabaseMain $db, $whereas, $data){
		$db->query("START TRANSACTION;");
		$html="";

		if (empty($data['Nom']) || empty($data['debut']) || empty($data['fin'])) {
			$this->errorForm->addError('Veuillez remplir les champs manquants.');
		}
		if(!is_numeric($data['id'])){
			$this->errorForm->addError("L'élément que vous voulez mettre à jour n'existe pas.");
		}
		if (!preg_match('/^[a-zA-ZÀ-ÿ\s]+$/', $data['Nom'])) {
			$this->errorForm->addError('Le nom ne doit pas contenir de caractères spéciaux.');
		}
		if (strlen($data['debut']) == 0) {
			$this->errorForm->addError('Le format de la date de début est invalide.');
		}
		if (strlen($data['fin']) == 0) {
			$this->errorForm->addError('Le format de la date de fin est invalide.');
		}
		$startDate = new DateTime($data['debut']);
		$endDate = new DateTime($data['fin']);
		if ($startDate == $endDate) {
			$this->errorForm->addError('La date de début doit être différente de la date de fin.');
		} elseif ($startDate > $endDate) {
			$this->errorForm->addError('La date de début doit être inférieure à la date de fin.');
		}
		$data = array(
			'Nom' => '\'' . $db->escape($data['Nom']) . '\'',
			'debut' => '\'' . $db->escape($data['debut']) . '\'',
			'fin' => '\'' . $db->escape($data['fin']) . '\'',
		);
		if(count($this->errorForm->getArray()) == 0){
			$res = $db->update("vacances", $whereas, $data);
			$this->model->updateEventStatusToHoliday($db);
			//On vérifie s'il s'agit d'un échec ou non
			if (!$res instanceof DatabaseResult) {
				$db->query("ROLLBACK;");
				return getAlert('danger', array('Erreur lors de la modification'));
			} else {
				$db->query("COMMIT;");
				$html .= getAlert('success modification', array('Modification fait avec succès'));
			}
		}
		else{
			$db->query("ROLLBACK;");
			$html .= getAlert('danger', $this->errorForm->getArray());
		}
		return $html;
	}
	public function deleteHoliday(DatabaseMain $db, array $whereas, $id){
		$db->query("START TRANSACTION;");
		$res = $db->update("vacances", $whereas, array("is_activ" => "0"));
		$holidatToDelete = $this->getHolidayInfo($db, $id);
		$updateEventRes = $this->model->updateEventStatusToHoliday($db, true,$holidatToDelete[0]['debut'], $holidatToDelete[0]['fin']);
		//On vérifie s'il s'agit d'un échec ou non
		if (!$res instanceof DatabaseResult || !$updateEventRes instanceof DatabaseResult) {
			$db->query("ROLLBACK;");
			return json_encode(getAlert('danger', array('Erreur lors de la modification')));
		} else {
			$db->query("COMMIT;");
			$html = getAlert('success modification', array("l'élément a été supprimé avec succès."));
		}
		return $html;

	}

}